<?php
/* =====================================================================
   otsing.php — TOOTE OTSING
   ---------------------------------------------------------------------
   HAAVATAVUS: otsingusõna "q" pannakse otse LIKE-päringusse.
   Lisaks kasutab see fail mysqli_multi_query(), mis lubab mitu
   SQL-lauset korraga ("stacked queries"). Nii saab õppija
   demonstreerida andmete MUUTMIST ja kustutamist, nt:

      otsing.php?q=klaviatuur'; UPDATE tooted SET hind=0; --
      otsing.php?q=x'; DELETE FROM logid; --

   ÕPPEMÄRKUS: tavaline mysqli_query() lubab korraga ainult ÜHE lause,
   seega seal stacked query ei tööta. multi_query() on siin tahtlikult
   kasutusel, et näidata, kui ohtlik on mitme lause lubamine koos
   kaitseta sisendiga. Päris rakenduses EI kasutata multi_query()-d
   kasutaja sisendiga kunagi.
   ===================================================================== */
require 'config.php';

$q       = $_GET['q'] ?? '';
$ridad   = [];
$teated  = [];
$sql     = '';

if ($q !== '') {
    // KAITSETA päring — otsingusõna liidetakse otse LIKE-mustrisse
    $sql = "SELECT id, nimi, kirjeldus, hind FROM tooted
            WHERE nimi LIKE '%$q%' OR kirjeldus LIKE '%$q%'";

    // multi_query lubab mitu lauset (; eraldab) — vajalik manipuleerimise demoks
    if (mysqli_multi_query($conn, $sql)) {
        do {
            if ($res = mysqli_store_result($conn)) {
                while ($r = mysqli_fetch_assoc($res)) {
                    $ridad[] = $r;
                }
                mysqli_free_result($res);
            }
        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
    }

    if (mysqli_error($conn)) {
        $teated[] = 'Andmebaasi viga: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Otsing</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;background:#f6f7f9;color:#1b1f24}
        input{padding:.5rem;border:1px solid #c7ccd1;border-radius:6px;width:60%}
        button{padding:.5rem 1rem;background:#1a5fb4;color:#fff;border:0;border-radius:6px;cursor:pointer}
        .kaart{background:#fff;padding:.7rem 1rem;border-radius:8px;margin:.4rem 0;border:1px solid #e4e7eb}
        .debug{background:#0d1117;color:#8be9fd;padding:.6rem .8rem;border-radius:6px;font-family:monospace;font-size:.8rem;overflow-x:auto;margin-top:1rem}
        .viga{background:#ffe8e8;border:1px solid #f1b0b0;padding:.6rem .8rem;border-radius:6px;color:#8a1f1f}
        a{color:#1a5fb4}
    </style>
</head>
<body>
    <p><a href="index.php">&larr; Avaleht</a></p>
    <h1>Otsi toodet</h1>
    <form method="get">
        <input type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="nt klaviatuur">
        <button type="submit">Otsi</button>
    </form>

    <?php
    foreach ($teated as $t) {
        echo '<p class="viga">' . htmlspecialchars($t) . '</p>';
    }
    if ($q !== '') {
        echo '<p class="debug">SQL: ' . htmlspecialchars($sql) . '</p>';
        echo '<p>Leitud ' . count($ridad) . ' rida.</p>';
        foreach ($ridad as $r) {
            echo '<div class="kaart"><strong>' . htmlspecialchars($r['nimi']) . '</strong><br>'
               . htmlspecialchars($r['kirjeldus']) . '</div>';
        }
    }
    ?>
</body>
</html>
