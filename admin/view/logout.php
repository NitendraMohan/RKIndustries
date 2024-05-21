<?php
require_once '../connection.inc.php';
unset($_SESSION['islogin']);
unset($_SESSION['username']);
// print_r($_SESSION);
// die();
// session_start();
// session_destroy();
header('location:../login.php');
// die();

?>