<?php
require 'config.php';
applog('LOGOUT');
session_destroy();
header('Location: login.php');
