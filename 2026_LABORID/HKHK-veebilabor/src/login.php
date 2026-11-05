<?php
// HAAVATAV: SQL Injection – kasutaja sisend liidetakse otse SQL-lausesse
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = $_POST['username'] ?? '';
    $p = $_POST['password'] ?? '';

    $sql = "SELECT id, username, role FROM users WHERE username = '$u' AND password = MD5('$p')";
    applog("LOGIN SQL: $sql");

    $row = $pdo->query($sql)->fetch();

    if ($row) {
        $_SESSION['uid'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        applog("LOGIN OK uid={$row['id']}");
        header('Location: tickets.php');
        exit;
    }
    applog("LOGIN FAIL username=$u");
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
