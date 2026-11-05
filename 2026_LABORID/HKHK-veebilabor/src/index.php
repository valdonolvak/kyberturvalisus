<?php
require 'config.php';
header('Location: ' . (empty($_SESSION['uid']) ? 'login.php' : 'tickets.php'));
