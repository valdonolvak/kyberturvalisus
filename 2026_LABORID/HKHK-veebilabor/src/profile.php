<?php
// HAAVATAV: CSRF
// 1) olekut muutev toiming aktsepteerib ka GET-päringut ($_REQUEST)
// 2) puudub CSRF-token
// 3) sessiooniküpsisel puudub SameSite atribuut
require 'config.php';
require_login();

$msg = '';
if (isset($_REQUEST['email'])) {
    $email = $_REQUEST['email'];
    $ref = $_SERVER['HTTP_REFERER'] ?? '-';
    applog("PROFILE EMAIL CHANGE method={$_SERVER['REQUEST_METHOD']} new=$email referer=$ref");
    $st = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
    $st->execute([$email, $_SESSION['uid']]);
    $msg = 'E-posti aadress muudetud.';
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
    <label for="email">Uus e-posti aadress</label>
    <input type="email" id="email" name="email">
    <button type="submit">Salvesta</button>
  </form>
  <?php if ($msg): ?><p class="ok"><?= e($msg) ?></p><?php endif; ?>
</div>
<?php page_footer();
