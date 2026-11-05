<?php
require 'config.php';
require_login();

$st = $pdo->prepare('SELECT id, title, created FROM tickets WHERE user_id = ? ORDER BY id');
$st->execute([$_SESSION['uid']]);
$rows = $st->fetchAll();

page_header('Minu pöördumised');
?>
<table>
  <tr><th>ID</th><th>Pealkiri</th><th>Loodud</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= e($r['id']) ?></td>
    <td><a href="ticket.php?id=<?= e($r['id']) ?>"><?= e($r['title']) ?></a></td>
    <td><?= e($r['created']) ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php page_footer();
