<?php
require_once 'db.php';

header('Content-Type: application/javascript; charset=UTF-8');
header('Cache-Control: public, max-age=300');
header('Access-Control-Allow-Origin: *');

$uid = $_GET['uid'] ?? '';
if (!preg_match('/^[a-f0-9]{32}$/', $uid)) {
    echo "console.warn('KitePad embed: invalid uid.');";
    exit;
}

$stmt = $pdo->prepare("SELECT uid, title, content, is_encrypted, password_hash, exposure, expires_at FROM writings WHERE uid = ? LIMIT 1");
$stmt->execute([$uid]);
$row = $stmt->fetch();

if (!$row || $row['exposure'] !== 'public' || (!empty($row['expires_at']) && strtotime($row['expires_at']) <= time())) {
    echo "console.warn('KitePad embed: post unavailable.');";
    exit;
}

$title = $row['title'] ?: 'KitePad Paste';

if (!empty($row['is_encrypted']) || !empty($row['password_hash'])) {
    $text = 'This content is protected and cannot be embedded.';
} else {
    $text = mb_substr($row['content'], 0, 3000);
}

$titleJson = json_encode($title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$textJson = json_encode($text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$urlJson = json_encode((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/v/' . $row['uid']);

echo "(function(){\n";
echo "  var s=document.currentScript; if(!s){return;}\n";
echo "  var host=s.parentNode||document.body;\n";
echo "  var wrap=document.createElement('div');\n";
echo "  wrap.style.cssText='background:#161616;color:#fff;border:1px solid #2a2a2a;border-radius:10px;padding:16px;font-family:Exo 2,system-ui,sans-serif;max-width:740px;';\n";
echo "  var title=document.createElement('div'); title.textContent=" . $titleJson . "; title.style.cssText='font-weight:700;margin:0 0 10px 0;font-size:18px;';\n";
echo "  var body=document.createElement('pre'); body.textContent=" . $textJson . "; body.style.cssText='white-space:pre-wrap;word-break:break-word;margin:0;max-height:420px;overflow:auto;line-height:1.45;font-size:14px;';\n";
echo "  var foot=document.createElement('a'); foot.href=" . $urlJson . "; foot.textContent='Open in KitePad'; foot.style.cssText='display:inline-block;margin-top:12px;color:#ddd;text-decoration:underline;font-size:13px;';\n";
echo "  wrap.appendChild(title);wrap.appendChild(body);wrap.appendChild(foot);host.insertBefore(wrap,s);\n";
echo "})();";
?>
