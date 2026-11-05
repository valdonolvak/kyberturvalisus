<?php
// Kahjutu testfail üleslaadimise labori jaoks.
// Kui see fail käivitub serveris, näed serveri andmeid – see TÕENDAB koodi käivitamist.
echo "HKHK LABOR: see PHP-kood kaivitati serveris!\n";
echo "Kasutaja: " . get_current_user() . "\n";
echo "Server: " . php_uname() . "\n";
