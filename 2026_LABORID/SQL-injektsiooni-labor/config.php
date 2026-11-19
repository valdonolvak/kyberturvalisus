<?php
/* =====================================================================
   config.php — andmebaasiühendus
   SQL-süstimise labor (AINULT laborikeskkonnas!)
   ===================================================================== */

// Andmebaasi ühenduse parameetrid.
// Loeme need keskkonnamuutujatest, kui need on seatud (Docker), muidu
// kasutame vaikeväärtusi (otse Ubuntu/Debiani serveris = localhost).
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_USER = getenv('DB_USER') ?: 'veebipood';
$DB_PASS = getenv('DB_PASS') ?: 'veebipood';
$DB_NAME = getenv('DB_NAME') ?: 'pood';

// Loome ühenduse MySQLi-ga (MySQL Improved laiend)
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Kontrollime ühendust
if (!$conn) {
    die('Andmebaasiühendus ebaõnnestus: ' . mysqli_connect_error());
}

// Määrame märgistiku (utf8mb4 = täielik UTF-8 tugi, sh täpitähed)
mysqli_set_charset($conn, 'utf8mb4');
