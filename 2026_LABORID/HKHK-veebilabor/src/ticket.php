<?php
// HAAVATAV: Broken Access Control (IDOR)
// Server kontrollib, et kasutaja on sisse loginud, aga MITTE seda, kas pöördumine kuulub talle.
require 'config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
applog("TICKET VIEW id=$id uid={$_SESSION['uid']}");

$st = $pdo->prepare('SELECT t.*, u.username FROM tickets t JOIN users u ON u.id = t.user_id WHERE t.id = ?');
$st->execute([$id]);
$t = $st->fetch();

if (!$t) {
    http_response_code(404);
    page_header('Pöördumist ei leitud');
    page_footer();
    exit;
}

page_header('Pöördumine #' . $t['id']);
?>
<div class="box">
  <p><b>Pealkiri:</b> <?= e($t['title']) ?></p>
  <p><b>Omanik:</b> <?= e($t['username']) ?></p>
  <p><b>Sisu:</b> <?= e($t['body']) ?></p>
  <p><b>Loodud:</b> <?= e($t['created']) ?></p>
</div>
<?php page_footer();
