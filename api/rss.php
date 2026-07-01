<?php
require_once 'db.php';

header('Content-Type: application/rss+xml; charset=UTF-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scheme = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
$base = $scheme . $host;

$stmt = $pdo->query("SELECT uid, title, content, created_at FROM writings
    WHERE (expires_at IS NULL OR expires_at > NOW())
    AND exposure = 'public'
    ORDER BY created_at DESC
    LIMIT 50");
$items = $stmt->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
  <title>KitePad Public Posts</title>
  <description>Latest public posts from KitePad</description>
  <link><?= htmlspecialchars($base . '/view', ENT_XML1, 'UTF-8') ?></link>
  <atom:link href="<?= htmlspecialchars($base . '/rss', ENT_XML1, 'UTF-8') ?>" rel="self" type="application/rss+xml"/>
  <language>en-us</language>
  <?php foreach ($items as $item): ?>
  <?php
    $itemTitle = $item['title'] ?: 'Untitled';
    $itemLink = $base . '/v/' . $item['uid'];
    $desc = mb_substr(trim(preg_replace('/\s+/', ' ', strip_tags($item['content']))), 0, 400);
  ?>
  <item>
    <title><?= htmlspecialchars($itemTitle, ENT_XML1, 'UTF-8') ?></title>
    <link><?= htmlspecialchars($itemLink, ENT_XML1, 'UTF-8') ?></link>
    <guid><?= htmlspecialchars($itemLink, ENT_XML1, 'UTF-8') ?></guid>
    <description><?= htmlspecialchars($desc, ENT_XML1, 'UTF-8') ?></description>
    <pubDate><?= date(DATE_RSS, strtotime($item['created_at'])) ?></pubDate>
  </item>
  <?php endforeach; ?>
</channel>
</rss>
