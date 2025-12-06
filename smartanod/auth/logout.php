<?php
// auth/logout.php
require_once '../config.php';

session_destroy();
redirect(BASE_URL . '/auth/login.php');
?>