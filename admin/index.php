<?php
require_once '../admin/connection.inc.php';
require_once '../admin/utility/sessions.php';
$username = checkUserSession();
?>
<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Dashboard Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body>
      <?php
      require('template/leftpanel.php');
      ?>
      <div id="right-panel" class="right-panel">
         <?php
         require('template/top.php');
         ?>
         <div class="content pb-0">
            <div class="orders">
               <div class="row">
                  <div class="col-xl-12">
                     <div class="card">
                        <div class="card-body">
                           <h4 class="box-title">Orders </h4>
                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <table class="table ">
                                 <thead>
                                    <tr>
                                       <th class="serial">#</th>
                                       <th class="avatar">Avatar</th>
                                       <th>ID</th>
                                       <th>Name</th>
                                       <th>Product</th>
                                       <th>Quantity</th>
                                       <th>Status</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td class="serial">1.</td>
                                       <td class="avatar">
                                          <div class="round-img">
                                             <a href="#"><img class="rounded-circle" src="images/avatar/1.jpg" alt=""></a>
                                          </div>
                                       </td>
                                       <td> #5469 </td>
                                       <td> <span class="name">Louis Stanley</span> </td>
                                       <td> <span class="product">iMax</span> </td>
                                       <td><span class="count">231</span></td>
                                       <td>
                                          <span class="badge badge-complete">Complete</span>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="serial">2.</td>
                                       <td class="avatar">
                                          <div class="round-img">
                                             <a href="#"><img class="rounded-circle" src="images/avatar/2.jpg" alt=""></a>
                                          </div>
                                       </td>
                                       <td> #5468 </td>
                                       <td> <span class="name">Gregory Dixon</span> </td>
                                       <td> <span class="product">iPad</span> </td>
                                       <td><span class="count">250</span></td>
                                       <td>
                                          <span class="badge badge-complete">Complete</span>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="serial">3.</td>
                                       <td class="avatar">
                                          <div class="round-img">
                                             <a href="#"><img class="rounded-circle" src="images/avatar/3.jpg" alt=""></a>
                                          </div>
                                       </td>
                                       <td> #5467 </td>
                                       <td> <span class="name">Catherine Dixon</span> </td>
                                       <td> <span class="product">SSD</span> </td>
                                       <td><span class="count">250</span></td>
                                       <td>
                                          <span class="badge badge-complete">Complete</span>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td class="serial">4.</td>
                                       <td class="avatar">
                                          <div class="round-img">
                                             <a href="#"><img class="rounded-circle" src="images/avatar/4.jpg" alt=""></a>
                                          </div>
                                       </td>
                                       <td> #5466 </td>
                                       <td> <span class="name">Mary Silva</span> </td>
                                       <td> <span class="product">Magic Mouse</span> </td>
                                       <td><span class="count">250</span></td>
                                       <td>
                                          <span class="badge badge-pending">Pending</span>
                                       </td>
                                    </tr>
                                    <tr class=" pb-0">
                                       <td class="serial">5.</td>
                                       <td class="avatar pb-0">
                                          <div class="round-img">
                                             <a href="#"><img class="rounded-circle" src="images/avatar/6.jpg" alt=""></a>
                                          </div>
                                       </td>
                                       <td> #5465 </td>
                                       <td> <span class="name">Johnny Stephens</span> </td>
                                       <td> <span class="product">Monitor</span> </td>
                                       <td><span class="count">250</span></td>
                                       <td>
                                          <span class="badge badge-complete">Complete</span>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
		  </div>
         <div class="clearfix"></div>
         <?php
         require('template/footer.php');
         ?>
      </div>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
   </body>
</html>