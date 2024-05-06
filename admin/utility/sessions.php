<?php
session_start();
function checkUserSession(){
    if(($_SESSION['islogin']) || $_SESSION['islogin'] == true){
      $username = $_SESSION['username'];
      return $username;
    }
    else{
       echo $_SESSION['islogin'];
       header('location:login.php');
       return null;
    }

}
?>