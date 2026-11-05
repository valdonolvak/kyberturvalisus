<?php
// PARANDATUD: üleslaaditud piltide turvaline kuvamine (uus fail)
require 'config.php';
require_login();

$name = $_GET['f'] ?? '';

// Ainult serveri genereeritud kujul nimi: 32 hex-märki + lubatud laiend
if (!preg_match('/^[a-f0-9]{32}\.(png|jpg|gif)$/', $name, $m)) {
    http_response_code(404);
    exit('Faili ei leitud.');
}

$path = '/var/www/private-uploads/' . $name;
if (!is_file($path)) {
    http_response_code(404);
    exit('Faili ei leitud.');
}

$types = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'gif' => 'image/gif'];
header('Content-Type: ' . $types[$m[1]]);
header('Content-Disposition: inline; filename="' . $name . '"');
readfile($path);
