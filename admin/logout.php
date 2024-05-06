<?php
require_once '../admin/connection.inc.php';
unset($_SESSION['islogin']);
header('location:login.php');
// die();

?>