<?php
require_once(dirname(__FILE__) . '/../../config.php');
require_once '../utility/sessions.php';
require_once '../connection.inc.php';
//lodar record inside table
$db = new dbConnector();
$sql = "select m.*,mm.main_title from tbl_modules m
         inner join tbl_main_menu mm
         on m.main_id=mm.id
         inner join tbl_user_permissions p 
         on m.id=p.moduleid and p.userid={$_SESSION['userid']} 
         and (p.insert_record=1 or p.update_record=1 or p.delete_record=1)
         group by m.main_id,m.id order by mm.id, m.order_no";
$result = $db->readData($sql);
$menu = array();
foreach ($result as $record) {
   $menu[$record['main_title']][]= $record;
   # code...
}
// echo '<pre>';
$username = checkUserSession();
if (!isset($username)) {
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
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_NORMALIZE; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_BOOSTRAPMIN; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_FONTAWSOMEMIN; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_THEMIFYICONS; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_PEICONFILLED; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_FLAGICONMIN; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_SKINELASTIC; ?>">
   <link rel="stylesheet" href="<?php echo CSS_DEFAULT_STYLE; ?>">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   <style>
      td.placeholder1 {
         color: #999;
      }
      .sidebar li .submenu{ 
         list-style: none; 
         margin: 0; 
         padding: 0; 
         padding-left: .5rem; 
         padding-right: 1rem;
      }
   </style>
</head>

<body>
   <aside id="left-panel" class="left-panel">
      <nav class="sidebar navbar navbar-expand-sm navbar-default">
         <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav flex-column navbar-nav">
               <li class="menu-title">Menu</li>
               <div class="" btn-group-vertical>
                  <?php if(isset($menu)){
                     foreach ($menu as $key =>$records) {
                        echo "<li class='nav-item menu-title has-submenu'>
                        <a class='nav-link' href='#'> {$key}  </a>
                        <ul class='submenu collapse'>";
                        foreach($records as $row){
                           echo "<li><a class='nav-link' onclick='senddata({$row['id']}, \"{$row['module_name']}\", \"{$row['file_path']}\")' style='width:220px; margin-bottom:1px; border-radius:4px'>{$row['module_name']} </a></li>";   
                        }
                        echo "</ul>";
                     }
                  }
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
               <a class="navbar-brand" href="index.php"><img src="../images/rklogo.png" alt="Logo"></a>
               <a class="navbar-brand hidden" href="index.php"><img src="../images/rklogo.png" alt="Logo"></a>
               <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
            </div>
         </div>
         <div class="top-right">
            <div class="header-menu">
               <div class="user-area dropdown float-right">
                  <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Welcome <?php echo $username; ?></a>
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
         document.addEventListener("DOMContentLoaded", function(){
  document.querySelectorAll('.sidebar .nav-link').forEach(function(element){
    
    element.addEventListener('click', function (e) {

      let nextEl = element.nextElementSibling;
      let parentEl  = element.parentElement;	

        if(nextEl) {
            e.preventDefault();	
            let mycollapse = new bootstrap.Collapse(nextEl);
            
            if(nextEl.classList.contains('show')){
              mycollapse.hide();
            } else {
                mycollapse.show();
                // find other submenus with class=show
                var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                // if it exists, then close all of them
                if(opened_submenu){
                  new bootstrap.Collapse(opened_submenu);
                }
            }
        }
    }); // addEventListener
  }) // forEach
}); 
         function senddata(id, name, link) {
            $.ajax({
               url: link,
               type: "POST",
               data: {
                  moduleid: id,
                  modulename: name
               },
               success: function(result) {
                  window.location.href = link;
               },
               error: function(xhr, status, error) {
                  console.error("AJAX request failed:", status, error);
               }
            });
         }
      </script>