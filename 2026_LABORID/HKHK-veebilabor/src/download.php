<?php
// HAAVATAV: Path Traversal – kasutaja määratud failinimi liidetakse otse failiteega
require 'config.php';
require_login();

$file = $_GET['file'] ?? '';

if ($file === '') {
    page_header('Juhendid');
    echo "<div class='box'><ul>";
    foreach (scandir(__DIR__ . '/files') as $f) {
        if ($f[0] === '.') continue;
        echo "<li><a href='download.php?file=" . urlencode($f) . "'>" . e($f) . "</a></li>";
    }
    echo "</ul></div>";
    page_footer();
    exit;
}

$path = __DIR__ . '/files/' . $file;
applog("DOWNLOAD path=$path");

if (!is_file($path)) {
    http_response_code(404);
    echo 'Faili ei leitud.';
    exit;
}

header('Content-Type: text/plain; charset=utf-8');
readfile($path);
