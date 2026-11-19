<?php
/* =====================================================================
   toode.php — ÜKSIKU TOOTE VAADE
   ---------------------------------------------------------------------
   HAAVATAVUS: GET-parameeter "id" pannakse OTSE SQL-päringusse ilma
   mingi kontrolli, tüübiteisenduse ega parameetristamiseta.
   See võimaldab URL-i kaudu SQL-süstimist (SQL injection).

   Näide normaalsest kasutusest:  toode.php?id=1
   Näide rünnakust:               toode.php?id=1 OR 1=1
   ===================================================================== */
require 'config.php';

// Võtame id-parameetri URL-ist nii nagu see on — MITTE MINGIT KAITSET
$id = $_GET['id'] ?? '1';

// Ehitame päringu stringide liitmisega (= klassikaline viga!)
$sql = "SELECT id, nimi, kirjeldus, hind, laos FROM tooted WHERE id = $id";
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Toode</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;background:#f6f7f9;color:#1b1f24}
        .kaart{background:#fff;padding:1rem 1.25rem;border-radius:8px;margin:.5rem 0;border:1px solid #e4e7eb}
        .debug{background:#0d1117;color:#8be9fd;padding:.6rem .8rem;border-radius:6px;font-family:monospace;font-size:.82rem;overflow-x:auto}
        .viga{background:#ffe8e8;border:1px solid #f1b0b0;padding:.6rem .8rem;border-radius:6px;color:#8a1f1f}
        a{color:#1a5fb4}
    </style>
</head>
<body>
    <p><a href="index.php">&larr; Tagasi</a></p>

    <!-- Laboris näitame täidetud päringut, et õppija näeks, mida ta süstib.
         Päris rakenduses EI TOHI kunagi päringut ega vigu kasutajale näidata. -->
    <p class="debug">SQL: <?php echo htmlspecialchars($sql); ?></p>

    <?php
    // Käivitame päringu. mysqli_query() lubab korraga AINULT ühe lause,
    // seega "stacked query" (nt ; DROP TABLE) siin ei tööta — selle
    // demonstreerimiseks vaata faili otsing.php (multi_query).
    $tulemus = mysqli_query($conn, $sql);

    if (!$tulemus) {
        // Veateate kuvamine teeb veapõhise (error-based) süstimise võimalikuks
        echo '<p class="viga">Andmebaasi viga: ' . mysqli_error($conn) . '</p>';
    } elseif (mysqli_num_rows($tulemus) === 0) {
        echo '<p>Toodet ei leitud.</p>';
    } else {
        while ($rida = mysqli_fetch_assoc($tulemus)) {
            echo '<div class="kaart">';
            echo '<h2>' . htmlspecialchars($rida['nimi']) . '</h2>';
            echo '<p>' . htmlspecialchars($rida['kirjeldus']) . '</p>';
            echo '<p><strong>Hind:</strong> ' . htmlspecialchars($rida['hind']) . ' €</p>';
            echo '<p><strong>Laos:</strong> ' . htmlspecialchars($rida['laos']) . ' tk</p>';
            echo '</div>';
        }
    }
    ?>
</body>
</html>
