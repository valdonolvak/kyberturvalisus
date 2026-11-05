<?php
// HAAVATAV: Reflected XSS – otsingusõna kuvatakse lehel kodeerimata kujul
// NB! SQL-päring ise on siin turvaline (prepared statement). Probleem on VÄLJUNDIS.
require 'config.php';
require_login();

$q = $_GET['q'] ?? '';
$rows = [];
if ($q !== '') {
    applog("SEARCH q=$q");
    $st = $pdo->prepare('SELECT id, title FROM tickets WHERE user_id = ? AND title LIKE ?');
    $st->execute([$_SESSION['uid'], '%' . $q . '%']);
    $rows = $st->fetchAll();
}

page_header('Otsing');
?>
<div class="box">
  <form method="get" action="search.php">
    <label for="q">Otsi oma pöördumistest</label>
    <input type="text" id="q" name="q">
    <button type="submit">Otsi</button>
  </form>
</div>
<?php if ($q !== ''): ?>
  <p>Otsingu tulemused päringule: <b><?= $q ?></b></p>
  <ul>
  <?php foreach ($rows as $r): ?>
    <li><a href="ticket.php?id=<?= e($r['id']) ?>"><?= e($r['title']) ?></a></li>
  <?php endforeach; ?>
  </ul>
  <?php if (!$rows): ?><p>Tulemusi ei leitud.</p><?php endif; ?>
<?php endif;
page_footer();
