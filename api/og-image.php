<?php
header('Content-Type: image/svg+xml; charset=UTF-8');
header('Cache-Control: public, max-age=3600');

$title = trim($_GET['title'] ?? 'KitePad');
if ($title === '') {
    $title = 'KitePad';
}
$title = mb_substr($title, 0, 90);
$safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
?>
<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="630" viewBox="0 0 1200 630" role="img" aria-label="KitePad preview image">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#0d0d0d" />
      <stop offset="100%" stop-color="#1e1e1e" />
    </linearGradient>
  </defs>
  <rect width="1200" height="630" fill="url(#bg)" />
  <rect x="48" y="48" width="1104" height="534" rx="20" fill="#161616" stroke="#2a2a2a" />
  <text x="88" y="128" fill="#9a9a9a" font-size="36" font-family="Segoe UI, Arial, sans-serif">KitePad</text>
  <text x="88" y="252" fill="#ffffff" font-size="62" font-family="Segoe UI, Arial, sans-serif" font-weight="700"><?= $safeTitle ?></text>
  <text x="88" y="536" fill="#b0b0b0" font-size="28" font-family="Segoe UI, Arial, sans-serif">Zero-knowledge pastebin</text>
</svg>
