<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Labori-Pood — avaleht</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:760px;margin:2rem auto;padding:0 1rem;background:#f6f7f9;color:#1b1f24}
        h1{font-size:1.5rem}
        .hoiatus{background:#ffe8e8;border:1px solid #f1b0b0;padding:.75rem 1rem;border-radius:8px;font-size:.9rem}
        table{border-collapse:collapse;width:100%;background:#fff;border-radius:8px;overflow:hidden}
        th,td{padding:.6rem .8rem;border-bottom:1px solid #e4e7eb;text-align:left}
        th{background:#2b3440;color:#fff}
        a{color:#1a5fb4}
        nav a{margin-right:1rem}
    </style>
</head>
<body>
    <h1>Labori-Pood</h1>
    <p class="hoiatus"><strong>Õppelabor:</strong> see veebileht on tahtlikult
       haavatav ja mõeldud ainult isoleeritud laborikeskkonnas kasutamiseks.</p>

    <nav>
        <a href="index.php">Avaleht</a>
        <a href="otsing.php">Otsing</a>
        <a href="login.php">Logi sisse</a>
    </nav>

    <h2>Tooted</h2>
    <p>Kliki tootele, et näha üksikasju (vaata URL-i: <code>toode.php?id=1</code>).</p>
    <table>
        <tr><th>ID</th><th>Nimi</th><th>Hind</th><th>Laos</th></tr>
        <?php
        $tulemus = mysqli_query($conn, "SELECT id, nimi, hind, laos FROM tooted ORDER BY id");
        while ($rida = mysqli_fetch_assoc($tulemus)) {
            echo '<tr>';
            echo '<td>' . (int)$rida['id'] . '</td>';
            echo '<td><a href="toode.php?id=' . (int)$rida['id'] . '">'
                 . htmlspecialchars($rida['nimi']) . '</a></td>';
            echo '<td>' . number_format($rida['hind'], 2, ',', ' ') . ' €</td>';
            echo '<td>' . (int)$rida['laos'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</body>
</html>
