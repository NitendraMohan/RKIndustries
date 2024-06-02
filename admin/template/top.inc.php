<?php
require_once (dirname(__FILE__) . '/../../config.php');
require_once '../utility/sessions.php';
require_once '../connection.inc.php';
//lodar record inside table
$db = new dbConnector();
$sql = "select m.* from tbl_modules m inner join tbl_user_permissions p on m.id=p.moduleid and p.userid={$_SESSION['userid']} and (p.insert_record=1 or p.update_record=1 or p.delete_record=1)";
$result = $db->readData($sql);
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
                  <div class=""btn-group-vertical >
                     <?php if(isset($result)){ foreach($result as $row) {
                        echo "<li class='menu-item-has-children dropdown'>
                        <button type='button' class='btn btn-info' onclick='senddata({$row['id']}, \"{$row['module_name']}\", \"{$row['file_path']}\")' style='width:220px; margin-bottom:1px;'>{$row['module_name']}</button>
                        </li>";   
                     }}
                     ?>
                  </div>
               </ul>
            </div>
         </nav>
      </aside>
      <!-- <a href='{$row['file_path']}?moduleid={$row['id']}'> {$row['module_name']}</a> -->
      <div id="right-panel" class="right-panel">
         <header id="header" class="header">
            <div class="top-left">
               <div class="navbar-header">
                  <a class="navbar-brand" href="index.html"><img src="../images/rklogo.png" alt="Logo"></a>
                  <a class="navbar-brand hidden" href="index.html"><img src="../images/rklogo.png" alt="Logo"></a>
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
         <script src="../assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
         <script>
            // function senddata(){
               // jQuery.noConflict();
// $(document).ready(function () {
            function senddata(id, name, link){
            
               $.ajax({
                  url: link,
                  type: "POST",
                  data: { moduleid: id, modulename: name},
               success: function (result) {
                     window.location.href=link;
               // console.log(result);
               //  $("#usersTableContents").html(result);
               //  var total_records = $("#usersTableContents tr").length;
               //  $('#total_records').html("Total Records: "+total_records);
            },
            error: function (xhr, status, error) {
                console.error("AJAX request failed:", status, error);
            }
            });   
            }
         // });
         </script>