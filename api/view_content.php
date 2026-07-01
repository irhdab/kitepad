<?php
require_once 'db.php';

function is_bot_request() {
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    return (bool) preg_match('/(bot|crawl|spider|slack|whatsapp|telegram|facebookexternalhit|kakao|line|twitter|discord|apple)/i', $userAgent);
}

function verify_post_password($pdo, &$post, $password_attempt, $csrf_token) {
    if (empty($post['password_hash'])) return true;

    // Check session or cookie-based unlocking
    if (isset($_SESSION['unlocked_' . $post['id']]) || isset($_COOKIE['unlocked_' . $post['uid']])) {
        return true;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $password_attempt) {
        if (!check_rate_limit($pdo, "unlock_" . $post['uid'], 5, 900)) {
            $post['password_error'] = "Too many unlock attempts. Please wait 15 minutes and try again.";
            return false;
        }

        // CSRF check
        if (empty($csrf_token) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
            $post['password_error'] = "Invalid security token.";
            return false;
        }

        if (password_verify($password_attempt, $post['password_hash'])) {
            $_SESSION['unlocked_' . $post['id']] = true;
            setcookie('unlocked_' . $post['uid'], '1', [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            return true;
        }

        $post['password_error'] = "Incorrect password.";
    }

    $post['content'] = null; // Hide content if not verified
    return false;
}

function handle_single_view($pdo, $uid) {
    $sql = "SELECT id, uid, content, title, created_at, expires_at, password_hash, burn_on_read, view_count, view_limit, is_encrypted 
            FROM writings 
            WHERE uid = ? AND (expires_at IS NULL OR expires_at > NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$uid]);
    $post = $stmt->fetch();

    if (!$post) return [[], true, false, 1, null, ["👍" => 0, "🔥" => 0, "💯" => 0], false, true];

    $password_attempt = $_POST['password'] ?? null;
    $csrf_token = $_POST['csrf_token'] ?? '';
    $password_correct = verify_post_password($pdo, $post, $password_attempt, $csrf_token);
    $passwordError = $post['password_error'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
        if ($password_correct) {
            $pdo->prepare("DELETE FROM writings WHERE id = ?")->execute([$post['id']]);
            header("Location: /view");
            exit;
        }
    }

    if ($password_correct && !is_bot_request()) {
        $pdo->prepare("UPDATE writings SET view_count = view_count + 1 WHERE id = ?")->execute([$post['id']]);
        $post['view_count']++;

        $should_delete = !empty($post['burn_on_read']) || ($post['view_limit'] !== null && $post['view_count'] >= $post['view_limit']);
        if ($should_delete) {
            $pdo->prepare("DELETE FROM writings WHERE id = ?")->execute([$post['id']]);
        }
    }

    // API Handling
    if (isset($_GET['raw'])) {
        if ($password_correct) {
            header('Content-Type: text/plain; charset=utf-8');
            echo $post['content'];
        } else {
            http_response_code(401);
            echo "Unauthorized";
        }
        exit;
    }

    if (isset($_GET['json'])) {
        header('Content-Type: application/json');
        echo $password_correct ? json_encode($post) : json_encode(["message" => "Unauthorized"]);
        exit;
    }

    $reactionCounts = get_reaction_counts_for_uid($pdo, $post['uid']);

    return [[$post], true, false, 1, $passwordError, $reactionCounts, !empty($post['password_hash']), $password_correct];
}

function handle_list_view($pdo) {
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $limit = 15;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT id, uid, content, title, created_at, expires_at, password_hash, burn_on_read, view_count, view_limit, is_encrypted 
            FROM writings 
            WHERE (expires_at IS NULL OR expires_at > NOW()) AND exposure = 'public' 
            ORDER BY created_at DESC LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit + 1, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $posts = [];
    $has_next = false;
    $count = 0;

    while ($r = $stmt->fetch()) {
        if (++$count > $limit) {
            $has_next = true;
            break;
        }
        // Redact content for protected posts
        if (!empty($r['password_hash']) || !empty($r['burn_on_read']) || !empty($r['is_encrypted'])) {
            if (!empty($r['burn_on_read'])) $r['content'] = "[Burn on Read]";
            else if (!empty($r['is_encrypted'])) $r['content'] = "[Encrypted (E2EE)]";
            else $r['content'] = "[Password Protected]";
        }
        $posts[] = $r;
    }

    return [$posts, false, $has_next, $page];
}

try {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $uid = $_GET['id'] ?? null;
    $lang = strtolower($_GET['lang'] ?? $_COOKIE['kitepad_lang'] ?? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2));
    $lang = $lang === 'ko' ? 'ko' : 'en';
    setcookie('kitepad_lang', $lang, [
        'expires' => time() + 86400 * 365,
        'path' => '/',
        'samesite' => 'Lax'
    ]);

    $passwordError = null;
    $reactionCounts = ["👍" => 0, "🔥" => 0, "💯" => 0];
    $hasPassword = false;
    $passwordCorrect = true;
    $ogData = [
        'title' => 'KitePad',
        'description' => 'Zero-knowledge pastebin for secure sharing.',
        'url' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/view',
        'image' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/og-image?title=' . rawurlencode('KitePad')
    ];

    if ($uid) {
        list($result, $isSingle, $hasNext, $currentPage, $passwordError, $reactionCounts, $hasPassword, $passwordCorrect) = handle_single_view($pdo, $uid);
        if (!empty($result[0])) {
            $item = $result[0];
            $host = ($_SERVER['HTTP_HOST'] ?? 'localhost');
            $scheme = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $title = !empty($item['title']) ? $item['title'] : 'KitePad Paste';
            $description = 'Secure paste on KitePad';
            if (empty($item['is_encrypted']) && !empty($item['content'])) {
                $description = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($item['content']))), 0, 180);
            }
            $ogData = [
                'title' => $title,
                'description' => $description,
                'url' => $scheme . $host . '/v/' . $item['uid'],
                'image' => $scheme . $host . '/og-image?title=' . rawurlencode($title)
            ];
        }
    } else {
        list($result, $isSingle, $hasNext, $currentPage) = handle_list_view($pdo);
    }

    include 'view.phtml';
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("A database error occurred.");
}
?>
