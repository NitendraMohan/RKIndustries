<?php
require_once (dirname(__FILE__) . '/../../config.php');
require_once '../utility/sessions.php';
$username = checkUserSession();
if(!isset($username)){
   header('location:../login.php');
}
?>
<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_NORMALIZE;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_BOOSTRAPMIN;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_FONTAWSOMEMIN;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_THEMIFYICONS;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_PEICONFILLED;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_FLAGICONMIN;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_SKINELASTIC;?>">
      <link rel="stylesheet" href="<?php echo CSS_DEFAULT_STYLE;?>">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <style>
        td.placeholder1{
            color: #999;
         }
      </style>
   </head>
   <body>
   <aside id="left-panel" class="left-panel">
         <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
               <ul class="nav navbar-nav">
                  <li class="menu-title">Menu</li>
                  <li class="menu-item-has-children dropdown">
                     <a href="../view/sessionView.php"> Session Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="../view/companyView.php" > Company Master</a>
                  </li>
				      <li class="menu-item-has-children dropdown">
                     <a href="#" > Department Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="../view/usersView.php" > User Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="../view/unitView.php">Unit Master</a>
                  </li>
				      <li class="menu-item-has-children dropdown">
                     <a href="#" > Vendor Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="#" > Vendor Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="taxmaster.php" >Tax Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="categorymaster.php" >Category Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="limitmaster.php" >Limit Master</a>
                  </li>
                  <li class="menu-item-has-children dropdown">
                     <a href="../view/usersLogView.php" >User Log</a>
                  </li>
               </ul>
            </div>
         </nav>
      </aside>
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.html"><img src="../images/logo.png" alt="Logo"></a>
                  <a class="navbar-brand hidden" href="index.html"><img src="../images/logo2.png" alt="Logo"></a>
                  <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
               </div>
            </div>
            <div class="top-right">
               <div class="header-menu">
                  <div class="user-area dropdown float-right">
                  <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?php echo $username;?></a>
                     <div class="user-menu dropdown-menu">
                        <!-- <a class="nav-link" href="#"><i class="fa fa-power-off"></i>Logout</a> -->
                        <a class="nav-link" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
                     </div>
                  </div>
               </div>
            </div>
         </header>