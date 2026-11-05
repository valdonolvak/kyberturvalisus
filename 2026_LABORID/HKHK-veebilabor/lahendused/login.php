<?php
// PARANDATUD: SQL Injection
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    // PARANDUS: parameetriseeritud päring. Kasutaja sisend on alati ANDMED, mitte SQL-kood.
    $st = $pdo->prepare('SELECT id, username, role FROM users WHERE username = ? AND password = MD5(?)');
    $st->execute([$u, $p]);
    $row = $st->fetch();
    applog('LOGIN prepared statement, username=' . $u);

    if ($row) {
        session_regenerate_id(true);   // PARANDUS: uus sessiooni-ID pärast sisselogimist
        $_SESSION['uid'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        applog("LOGIN OK uid={$row['id']}");
        header('Location: tickets.php');
        exit;
    }
    applog('LOGIN FAIL username=' . $u);
    $error = 'Vale kasutajanimi või parool.';
}

page_header('Sisselogimine');
?>
<div class="box">
  <form method="post" action="login.php">
    <label for="username">Kasutajanimi</label>
    <input type="text" id="username" name="username">
    <label for="password">Parool</label>
    <input type="password" id="password" name="password">
    <button type="submit">Logi sisse</button>
  </form>
  <?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
  <p>Laborikonto: <code>mari</code> / <code>mari123</code></p>
</div>
<?php page_footer();
