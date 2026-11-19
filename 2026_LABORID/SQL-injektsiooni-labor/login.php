<?php
/* =====================================================================
   login.php — SISSELOGIMINE
   ---------------------------------------------------------------------
   HAAVATAVUS: kasutajanimi ja parool pannakse otse päringusse.
   Võimaldab autentimisest möödahiilimist (authentication bypass):
        kasutaja:  admin' --
        parool:    (ükskõik mis)
   ===================================================================== */
require 'config.php';

$sonum = '';
$sql   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kasutaja = $_POST['kasutaja'] ?? '';
    $parool   = $_POST['parool']   ?? '';

    // KAITSETA päring — kasutaja sisend liidetakse otse stringi
    $sql = "SELECT id, kasutaja, roll FROM kasutajad
            WHERE kasutaja = '$kasutaja' AND parool = '$parool'";

    $tulemus = mysqli_query($conn, $sql);

    if ($tulemus && mysqli_num_rows($tulemus) > 0) {
        $rida = mysqli_fetch_assoc($tulemus);
        $sonum = '<p style="color:#1a7f37">Sisselogimine õnnestus! Tere, '
               . htmlspecialchars($rida['kasutaja'])
               . ' (roll: ' . htmlspecialchars($rida['roll']) . ')</p>';
    } else {
        $sonum = '<p style="color:#8a1f1f">Vale kasutajanimi või parool.</p>';
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logi sisse</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:480px;margin:2rem auto;padding:0 1rem;background:#f6f7f9;color:#1b1f24}
        label{display:block;margin:.6rem 0 .2rem}
        input{width:100%;padding:.5rem;border:1px solid #c7ccd1;border-radius:6px}
        button{margin-top:1rem;padding:.6rem 1.2rem;background:#1a5fb4;color:#fff;border:0;border-radius:6px;cursor:pointer}
        .debug{background:#0d1117;color:#8be9fd;padding:.6rem .8rem;border-radius:6px;font-family:monospace;font-size:.8rem;overflow-x:auto;margin-top:1rem}
        a{color:#1a5fb4}
    </style>
</head>
<body>
    <p><a href="index.php">&larr; Avaleht</a></p>
    <h1>Logi sisse</h1>
    <form method="post">
        <label>Kasutajanimi</label>
        <input type="text" name="kasutaja" autofocus>
        <label>Parool</label>
        <input type="text" name="parool">
        <button type="submit">Logi sisse</button>
    </form>
    <?php
    echo $sonum;
    if ($sql !== '') {
        echo '<p class="debug">SQL: ' . htmlspecialchars($sql) . '</p>';
    }
    ?>
</body>
</html>
