<?php
// PARANDATUD: File Upload
require 'config.php';
require_login();

// PARANDUS 1: failid salvestatakse VÄLJASPOOL veebijuurt – Apache ei saa neid otse käivitada
const UPLOAD_DIR = '/var/www/private-uploads';
const MAX_SIZE = 2 * 1024 * 1024; // 2 MB

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['f'])) {
    $f = $_FILES['f'];
    applog("UPLOAD name={$f['name']} client_type={$f['type']} size={$f['size']}");

    // PARANDUS 2: tegelik MIME-tüüp tuvastatakse faili SISU järgi serveris
    $realType = ($f['error'] === UPLOAD_ERR_OK) ? (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']) : '';
    $map = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif'];

    if ($f['error'] !== UPLOAD_ERR_OK) {
        $msg = "<p class='err'>Üleslaadimine ebaõnnestus.</p>";
    } elseif ($f['size'] > MAX_SIZE) {                       // PARANDUS 3: suuruse piirang
        $msg = "<p class='err'>Fail on liiga suur (max 2 MB).</p>";
    } elseif (!isset($map[$realType])) {
        applog("UPLOAD REJECTED real_type=$realType");
        $msg = "<p class='err'>Lubatud on ainult pildifailid (PNG, JPEG, GIF).</p>";
    } else {
        // PARANDUS 4: failinime genereerib server, laiend tuleb tegelikust tüübist
        $name = bin2hex(random_bytes(16)) . '.' . $map[$realType];
        move_uploaded_file($f['tmp_name'], UPLOAD_DIR . '/' . $name);
        applog("UPLOAD SAVED $name real_type=$realType");
        $msg = "<p class='ok'>Fail salvestatud: <a href='view.php?f=" . e($name) . "'>" . e($name) . "</a></p>";
    }
}

page_header('Profiilifoto üleslaadimine');
?>
<div class="box">
  <form method="post" action="upload.php" enctype="multipart/form-data">
    <label for="f">Vali fail (PNG, JPEG, GIF, max 2 MB)</label>
    <input type="file" id="f" name="f" accept="image/png,image/jpeg,image/gif">
    <button type="submit">Laadi üles</button>
  </form>
  <?= $msg ?>
</div>
<?php page_footer();
