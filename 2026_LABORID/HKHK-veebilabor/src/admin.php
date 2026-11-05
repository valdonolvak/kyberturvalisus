<?php
require 'config.php';
require_login();

if (($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    applog('ADMIN DENIED');
    page_header('Ligipääs keelatud');
    echo "<p class='err'>Sul ei ole administraatori õigusi.</p>";
    page_footer();
    exit;
}

applog('ADMIN VIEW');
$rows = $pdo->query('SELECT id, username, full_name, email, role FROM users ORDER BY id')->fetchAll();

page_header('Administraatori vaade – kasutajad');
?>
<table>
  <tr><th>ID</th><th>Kasutaja</th><th>Nimi</th><th>E-post</th><th>Roll</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr><td><?= e($r['id']) ?></td><td><?= e($r['username']) ?></td><td><?= e($r['full_name']) ?></td>
      <td><?= e($r['email']) ?></td><td><?= e($r['role']) ?></td></tr>
  <?php endforeach; ?>
</table>
<?php page_footer();
