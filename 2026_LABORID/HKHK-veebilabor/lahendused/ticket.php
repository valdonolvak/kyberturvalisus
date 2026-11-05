<?php
// PARANDATUD: Broken Access Control (IDOR)
require 'config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);

// PARANDUS: omaniku kontroll on SQL-päringu osa. Admin võib näha kõiki pöördumisi.
if (($_SESSION['role'] ?? '') === 'admin') {
    $st = $pdo->prepare('SELECT t.*, u.username FROM tickets t JOIN users u ON u.id = t.user_id WHERE t.id = ?');
    $st->execute([$id]);
} else {
    $st = $pdo->prepare('SELECT t.*, u.username FROM tickets t JOIN users u ON u.id = t.user_id WHERE t.id = ? AND t.user_id = ?');
    $st->execute([$id, $_SESSION['uid']]);
}
$t = $st->fetch();

if (!$t) {
    // 404, mitte 403: ründaja ei saa teada, kas sellise ID-ga pöördumine üldse eksisteerib
    applog("TICKET DENIED id=$id uid={$_SESSION['uid']}");
    http_response_code(404);
    page_header('Pöördumist ei leitud');
    page_footer();
    exit;
}
applog("TICKET VIEW id=$id uid={$_SESSION['uid']}");

page_header('Pöördumine #' . $t['id']);
?>
<div class="box">
  <p><b>Pealkiri:</b> <?= e($t['title']) ?></p>
  <p><b>Omanik:</b> <?= e($t['username']) ?></p>
  <p><b>Sisu:</b> <?= e($t['body']) ?></p>
  <p><b>Loodud:</b> <?= e($t['created']) ?></p>
</div>
<?php page_footer();
