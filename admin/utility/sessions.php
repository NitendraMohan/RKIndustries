<?php
session_start();
function checkUserSession(){
    if(($_SESSION['islogin'])){
      $username = $_SESSION['username'];
      return $username;
    }
    else{
       header('location:login.php');
       return null;
    }

}
?>