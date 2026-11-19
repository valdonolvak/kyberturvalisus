<?php
/* =====================================================================
   toode_turvaline.php — PARANDATUD (turvaline) versioon failist toode.php
   ---------------------------------------------------------------------
   KAITSE: kasutame ettevalmistatud päringut (prepared statement) koos
   parameetrite sidumisega (bind_param). Kasutaja sisend EI SAA enam
   päringu struktuuri muuta — see jääb alati pelgalt andmeks.

   Proovi sama rünnakut mis toode.php puhul:
        toode_turvaline.php?id=1 OR 1=1
   Tulemus: rünnak EI tööta, sest "1 OR 1=1" tõlgendatakse täisarvuks.
   ===================================================================== */
require 'config.php';

// 1) Tüübiteisendus: ootame täisarvu, teisendame selle kohe int-iks
$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// 2) Ettevalmistatud päring kohatäitega (?)
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, nimi, kirjeldus, hind, laos FROM tooted WHERE id = ?"
);

// 3) Seome parameetri: "i" = integer (täisarv)
mysqli_stmt_bind_param($stmt, 'i', $id);

// 4) Käivitame ja loeme tulemuse
mysqli_stmt_execute($stmt);
$tulemus = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <title>Toode (turvaline)</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;background:#f6f7f9}
        .kaart{background:#fff;padding:1rem 1.25rem;border-radius:8px;border:1px solid #e4e7eb}
        a{color:#1a5fb4}
    </style>
</head>
<body>
    <p><a href="index.php">&larr; Tagasi</a></p>
    <p style="color:#1a7f37"><strong>Turvaline versioon:</strong> prepared statement + tüübiteisendus.</p>
    <?php
    if (mysqli_num_rows($tulemus) === 0) {
        echo '<p>Toodet ei leitud.</p>';
    } else {
        $rida = mysqli_fetch_assoc($tulemus);
        echo '<div class="kaart">';
        echo '<h2>' . htmlspecialchars($rida['nimi']) . '</h2>';
        echo '<p>' . htmlspecialchars($rida['kirjeldus']) . '</p>';
        echo '<p><strong>Hind:</strong> ' . htmlspecialchars($rida['hind']) . ' €</p>';
        echo '<p><strong>Laos:</strong> ' . htmlspecialchars($rida['laos']) . ' tk</p>';
        echo '</div>';
    }
    ?>
</body>
</html>
