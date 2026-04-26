<?php
session_start();
session_unset();
session_destroy();

setcookie('remember_id', '', time() - 3600, '/');
setcookie('remember_token', '', time() - 3600, '/');

header('Location: login.php');
exit;

