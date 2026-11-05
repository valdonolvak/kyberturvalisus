<?php
// PARANDATUD: CSRF
require 'config.php';
require_login();

$msg = '';
$err = '';

// PARANDUS 1: olekut muutev toiming ainult POST-meetodiga ($_POST, mitte $_REQUEST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // PARANDUS 2: CSRF-tokeni kontroll
    if (!csrf_check()) {
        http_response_code(403);
        applog('PROFILE CSRF TOKEN INVALID referer=' . ($_SERVER['HTTP_REFERER'] ?? '-'));
        $err = 'Päring lükati tagasi (vigane CSRF-token).';
    } else {
        $email = $_POST['email'] ?? '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = 'Vigane e-posti aadress.';
        } else {
            $st = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
            $st->execute([$email, $_SESSION['uid']]);
            applog('PROFILE EMAIL CHANGE method=POST new=' . $email);
            $msg = 'E-posti aadress muudetud.';
        }
    }
} elseif (isset($_GET['email'])) {
    applog('PROFILE EMAIL CHANGE REJECTED method=GET');
    http_response_code(405);
    $err = 'GET-meetodiga andmeid ei muudeta.';
}

$st = $pdo->prepare('SELECT username, full_name, email FROM users WHERE id = ?');
$st->execute([$_SESSION['uid']]);
$me = $st->fetch();

page_header('Profiil');
?>
<div class="box">
  <p><b>Kasutaja:</b> <?= e($me['username']) ?></p>
  <p><b>Nimi:</b> <?= e($me['full_name']) ?></p>
  <p><b>E-post:</b> <?= e($me['email']) ?></p>
  <form method="post" action="profile.php">
    <?= csrf_field() ?>
    <label for="email">Uus e-posti aadress</label>
    <input type="email" id="email" name="email">
    <button type="submit">Salvesta</button>
  </form>
  <?php if ($msg): ?><p class="ok"><?= e($msg) ?></p><?php endif; ?>
  <?php if ($err): ?><p class="err"><?= e($err) ?></p><?php endif; ?>
</div>
<?php page_footer();
