<?php
require_once '../admin/connection.inc.php';
require_once '../admin/utility/sessions.php';
$db = new dbConnector();
$username = checkUserSession();
if(isset($_GET['type']) && !empty($_GET['type'])){
   $type = $_GET['type'];
   $operation = $_GET['operation'];
   $id = $_GET['id'];
   if($type=='status'){
      $status = $operation == 'active' ? 1 : 0;
      $sql = "update financial_years set status=:status where id=:id";
      $params = ["status"=>$status,"id"=>$id];
      $rows = $db->ManageData($sql, $params);
   }
}

$sql = "select * from financial_years order by year_from desc";
$result = $db->readData($sql);
?>

<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Financial Year</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" hrSession Pageef="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
      <style>
        td.placeholder1{
            color: #999;
         }
      </style>
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
                        <div class="card-body" style="border-bottom: double">
                           <h4 class="box-title">Financial Years </h4>
                        </div>
                        <div class="card-body--">
                                 <!-- <button class="save btn btn-success" id="btnSave">Insert</button> -->
                                 
                           <button class="open-button btn btn-success" style="margin:20px;" onclick="openForm()">Create New</button>

                        </div>
                        <div class="card-body--">
                           <div class="table-stats order-table ov-h">
                              <!-- <table border="1" class="table">
                                 <thead>
                                    <tr>
                                       <th>Year from</th>
                                       <th>Year to</th>
                                       <th>Status</th>
                                    </tr>
                                 </thead>
                                 <tbody class="tableCreate">
                                 <form action="">
                                       <tr>
                                       <td><input class="yearlimit" type="number" min="1900" max="2100" step="1" placeholder="From Year" id="year_from" name="year_from" style="width: 100px;"></td>
                                       <td><input class="yearlimit" type="number" min="1900" max="2100" step="1" placeholder="Year To" id="year_to" name="year_to" style="width: 100px;" readonly tabindex="-1"></td>
                                       <td> 
                                          <select name="status" id="status">
                                             <option value="1">Active</option>
                                             <option value="0">Inactive</option>
                                          </select>
                                       </td>
                                    </tr>   
                                    </form>
                                 </tbody>
                              </table> -->

                              <!-- Add popup form -->
                              <div class="form-popup" id="myForm">
                                 <form action="" method="post" class="form-container">
                                    <h3>Finencial Year</h3>
                                       <div class="form-group">
                                          <label for="yearFrom"><b>Year From</b></label>
                                          <input class="form-control yearlimit" type="number" min="1900" max="2100" step="1" placeholder="From Year" id="year_from" name="year_from" width="200%" required>
                                       </div>
                                       <div class="form-group">
                                          <label for="yearTo"><b>Year to</b></label>
                                          <input class="yearlimit form-control" type="number" min="1900" max="2100" step="1" placeholder="Year To" id="year_to" name="year_to"  readonly tabindex="-1" required>
                                       </div>
                                    <div class="form-group">
                                    <label for="Status"><b>Status</b></label>
                                       <select class="form-control" name="status" id="status">
                                          <option value="1">Active</option>
                                          <option value="0">Inactive</option>
                                       </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="btnSave">Submit</button>
                                    <button type="button" class="btn btn-danger" onclick="closeForm()">Close</button>
                                 </form>
                              </div>
                              <!-- End popup form -->

                              
                              <table class="table">
                                 <thead>
                                    
                                    <tr>
                                       <th class="serial">#</th>
                                       <th style="display:none;">ID</th>
                                       <th>Year from</th>
                                       <th>Year to</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                    </tr>
                                 </thead>
                                 <tbody class="tableContents" id="tableContents">
                                 <?php
                                    $count = 1;
                                    foreach ($result as $row) { ?>
                                       <tr>
                                          <td class="serial"> <?php echo $count++."."?></td>
                                          <td style="display:none;"> <?php echo $row["id"]?> </td>
                                          <td > <span class="name"><?php echo $row["year_from"]?></span> </td>
                                          <td > <span class="product"><?php echo $row["year_to"]?></span> </td>
                                          <td ><span class="name">
                                             <?php echo $row["status"]==1
                                             ?"<a href='?type=status&operation=deactive&id=".$row['id']."'>Active</a>"
                                             :"<a href='?type=status&operation=active&id=".$row['id']."'>Inactive</a>"
                                             ?></span></td>
                                          <td>
                                             <button class="edit btn btn-success"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                             <button class="save btn btn-success" style="display:none;"><i class="fa fa-check" aria-hidden="true"></i></button>
                                             <button class="cancel btn btn-danger" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button>
                                             <button class="delete btn btn-warning"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                          </td>
                                       </tr>   
                                    <?php
                                    }
                                    ?>
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
      <script>
         function openForm() {
         document.getElementById("myForm").style.display = "block";
         }

         function closeForm() {
         document.getElementById("myForm").style.display = "none";
         }

      </script>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
      <script src="assets/js/custom.js" type="text/javascript"></script>
   </body>
</html>