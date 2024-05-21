<?php
require_once '../connection.inc.php';
session_start();
unset($_SESSION['islogin']);
unset($_SESSION['username']);
header('location:../login.php');
?>