<?php
// HKHK Tugi – konfiguratsioon (HAAVATAV VERSIOON)
// NB! Vead kuvatakse brauseris – see on samuti turvaprobleem (Security Misconfiguration).
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

$DB_HOST = getenv('DB_HOST') ?: 'db';
$DB_NAME = 'hkhk_tugi';
$DB_USER = 'hkhk_app';
$DB_PASS = 'HkhkLabor2026';

$pdo = new PDO(
    "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

// Rakenduse logi -> Apache error log -> "docker compose logs web"
function applog(string $msg): void
{
    $user = $_SESSION['username'] ?? '-';
    error_log("[HKHK] user=$user $msg");
}

function require_login(): void
{
    if (empty($_SESSION['uid'])) {
        header('Location: login.php');
        exit;
    }
}

// Turvaline väljundi kodeerimine (kasutatakse osades kohtades – mitte kõikjal!)
function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function page_header(string $title): void
{
    $u = $_SESSION['username'] ?? null;
    echo "<!doctype html><html lang='et'><head><meta charset='utf-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>" . e($title) . " – HKHK Tugi</title>";
    echo "<link rel='stylesheet' href='style.css'></head><body>";
    echo "<header><div class='wrap'><b>HKHK Tugi</b> <span class='lab'>LABOR – tahtlikult haavatav</span>";
    if ($u) {
        echo "<nav><a href='tickets.php'>Minu pöördumised</a><a href='search.php'>Otsing</a>";
        echo "<a href='download.php'>Juhendid</a><a href='upload.php'>Üleslaadimine</a>";
        echo "<a href='profile.php'>Profiil</a>";
        if (($_SESSION['role'] ?? '') === 'admin') {
            echo "<a href='admin.php'>Admin</a>";
        }
        echo "<a href='logout.php'>Logi välja (" . e($u) . ")</a></nav>";
    }
    echo "</div></header><main class='wrap'><h1>" . e($title) . "</h1>";
}

function page_footer(): void
{
    echo "</main></body></html>";
}
