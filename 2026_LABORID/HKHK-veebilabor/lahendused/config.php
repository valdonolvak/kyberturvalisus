<?php
// HKHK Tugi – konfiguratsioon (PARANDATUD VERSIOON)

// PARANDUS (misconfiguration): vigu ei kuvata kasutajale, need lähevad ainult logisse
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

// PARANDUS (XSS + CSRF): sessiooniküpsis
//  - HttpOnly: JavaScript ei saa küpsist lugeda (document.cookie)
//  - SameSite=Strict: brauser ei saada küpsist teiselt saidilt algatatud päringuga
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Strict',
    // 'secure' => true,   // lülita sisse, kui rakendus töötab HTTPS-iga
]);
session_start();

// PARANDUS (XSS): Content Security Policy – lubatakse ainult oma serveri skriptid
header("Content-Security-Policy: default-src 'self'; script-src 'self'; object-src 'none'; base-uri 'self'");
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

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
        PDO::ATTR_EMULATE_PREPARES => false,   // päris prepared statement'id
    ]
);

function applog(string $msg): void
{
    $user = $_SESSION['username'] ?? '-';
    // Eemaldame reavahetused, et kasutaja ei saaks logisse võltsridu lisada (log injection)
    $msg = str_replace(["\r", "\n"], ['\\r', '\\n'], $msg);
    error_log("[HKHK] user=$user $msg");
}

function require_login(): void
{
    if (empty($_SESSION['uid'])) {
        header('Location: login.php');
        exit;
    }
}

function e($s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

// PARANDUS (CSRF): sessioonipõhine token
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return "<input type='hidden' name='csrf' value='" . e(csrf_token()) . "'>";
}

function csrf_check(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']);
}

function page_header(string $title): void
{
    $u = $_SESSION['username'] ?? null;
    echo "<!doctype html><html lang='et'><head><meta charset='utf-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>" . e($title) . " – HKHK Tugi</title>";
    echo "<link rel='stylesheet' href='style.css'></head><body>";
    echo "<header><div class='wrap'><b>HKHK Tugi</b> <span class='lab'>LABOR – parandatud</span>";
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
