<?php
// HAAVATAV: Unrestricted File Upload
// 1) kontrollitakse ainult KLIENDI saadetud Content-Type väärtust
// 2) säilitatakse kasutaja antud failinimi
// 3) fail salvestatakse veebijuure alla, kust PHP saab seda käivitada
require 'config.php';
require_login();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['f'])) {
    $f = $_FILES['f'];
    applog("UPLOAD name={$f['name']} type={$f['type']} size={$f['size']}");

    $allowed = ['image/png', 'image/jpeg', 'image/gif'];
    if (!in_array($f['type'], $allowed, true)) {
        $msg = "<p class='err'>Lubatud on ainult pildifailid (PNG, JPEG, GIF).</p>";
    } else {
        $target = __DIR__ . '/uploads/' . $f['name'];
        move_uploaded_file($f['tmp_name'], $target);
        $url = 'uploads/' . rawurlencode($f['name']);
        $msg = "<p class='ok'>Fail salvestatud: <a href='$url'>$url</a></p>";
    }
}

page_header('Profiilifoto üleslaadimine');
?>
<div class="box">
  <form method="post" action="upload.php" enctype="multipart/form-data">
    <label for="f">Vali fail (PNG, JPEG, GIF)</label>
    <input type="file" id="f" name="f">
    <button type="submit">Laadi üles</button>
  </form>
  <?= $msg ?>
</div>
<?php page_footer();
