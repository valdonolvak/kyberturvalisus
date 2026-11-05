<?php
// PARANDATUD: Path Traversal
require 'config.php';
require_login();

$baseDir = realpath(__DIR__ . '/files');

// PARANDUS 1: lubatud failide nimekiri koostatakse serveris
$allowed = array_values(array_filter(scandir($baseDir), fn($f) => $f[0] !== '.'));

$file = $_GET['file'] ?? '';

if ($file === '') {
    page_header('Juhendid');
    echo "<div class='box'><ul>";
    foreach ($allowed as $f) {
        echo "<li><a href='download.php?file=" . urlencode($f) . "'>" . e($f) . "</a></li>";
    }
    echo "</ul></div>";
    page_footer();
    exit;
}

// PARANDUS 2: ainult lubenimekirjas olev nimi (../ ja / ei ole lubatud)
if (!in_array($file, $allowed, true)) {
    applog('DOWNLOAD DENIED file=' . $file);
    http_response_code(404);
    echo 'Faili ei leitud.';
    exit;
}

// PARANDUS 3 (kaitse sügavuti): tegelik tee peab asuma lubatud kataloogis
$path = realpath($baseDir . '/' . $file);
if ($path === false || !str_starts_with($path, $baseDir . DIRECTORY_SEPARATOR)) {
    applog('DOWNLOAD DENIED realpath file=' . $file);
    http_response_code(404);
    echo 'Faili ei leitud.';
    exit;
}

applog('DOWNLOAD path=' . $path);
header('Content-Type: text/plain; charset=utf-8');
readfile($path);
