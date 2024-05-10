<?php
require_once '../admin/connection.inc.php';
require_once '../admin/utility/sessions.php';
$db = new dbConnector();
$username = checkUserSession();
$editRecord = [];


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
                           <div class="table-stats order-table ov-h">
                              <div class="container">
                              <button type="button" class="btn btn-primary" style="margin:20px;" data-toggle="modal" data-target="#myModal" onclick="setModelValues('')">
                                 Create New
                                 </button>
                                 <!-- The Modal -->
                                 <div class="modal fade" id="myModal">
                                    <div class="modal-dialog modal-dialog-centered">
                                       <div class="modal-content">
                                       
                                       <!-- Modal Header -->
                                       <div class="modal-header">
                                       <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title">Add Financial Year</h4>
                                       </div>
                                       <!-- Modal body -->
                                       <div class="modal-body">
                                          <form action="" method="post" >
                                             <input type="hidden" id="modalid" name="id" value="" />
                                             <div class="form-group">
                                                <label for="yearFrom">Year From</label>
                                                <input class="form-control yearlimit modalyearfrom" type="number" min="1900" max="2100" step="1" placeholder="From Year" id="year_from" name="year_from" width="200%" required>
                                             </div>
                                             <div class="form-group">
                                                <label for="yearTo">Year to</label>
                                                <input class="yearlimit form-control modalyearto" type="number" min="1900" max="2100" step="1" placeholder="Year To" id="year_to" name="year_to" readonly tabindex="-1" required>
                                             </div>
                                             <div class="form-group">
                                             <label for="Status">Status</label>
                                                <select class="form-control modalyearstatus" name="status" id="status">
                                                   <option value="" selected>Select</option>   
                                                   <option value="1">Active</option>
                                                   <option value="0">Inactive</option>
                                                </select>
                                             </div>
                                          
                                       <!-- Modal footer -->
                                       <div class="modal-footer">
                                       <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
                                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                       <div class="alert alert-success" id="hmsg"></div>   
                                    </div>
                                    </form>
        
      </div>
    </div>
  </div>
  
</div>
                              
                              <table id="tblContents" class="table">
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
                              
                                    foreach ($result as $row) {
                                       $jsonArray = json_encode(($row)); ?>
                                       <tr>
                                          <td class="serial" data-id> <?php echo $count++."."?></td>
                                          <td class="id" style="display:none;"> <?php echo $row["id"]?> </td>
                                          <td class="year_from"> <span class="name"><?php echo $row["year_from"]?></span> </td>
                                          <td class="year_to"> <span class="product"><?php echo $row["year_to"]?></span> </td>
                                          <td class="status"><span class="name">
                                             <?php echo $row["status"]==1
                                             ?"<a href='?type=status&operation=deactive&id=".$row['id']."'>Active</a>"
                                             :"<a href='?type=status&operation=active&id=".$row['id']."'>Inactive</a>"
                                             ?></span></td>
                                          <td>
                                             <button class="edit btn btn-success"  data-toggle="modal" data-target="#myModal" onclick=setModelValues('<?php echo $jsonArray;?>')><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                             <!-- <button class="save btn btn-success" style="display:none;"><i class="fa fa-check" aria-hidden="true"></i></button> -->
                                             <!-- <button class="cancel btn btn-danger" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button> -->
                                             <button class="del btn btn-warning" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
      <script src="assets/js/custom.js" type="text/javascript"></script>
      <script>
         function setModelValues(row=''){
            if(row!=''){
               var jsArray = JSON.parse(row);
               $('.modalyearfrom').val(jsArray['year_from']);
               $('.modalyearto').val(jsArray['year_to']);
               $('.modalyearstatus').val(jsArray['status']);
               $('#modalid').val(jsArray['id']);
               $(".modal-title").text("Update Financial Year");
               $(".modalsubmit").attr("data-id","update");
            }
            else{
               $('.modalyearfrom').val('');
               $('.modalyearto').val('');
               $('.modalyearstatus').val('');
               $('#modalid').val('');
               $(".modal-title").text("Add Financial Year");
               $(".modalsubmit").attr("data-id","save");
               // $(".modalsubmit").attr("id","btnSave");
            }
         }
      </script>
   </body>
</html>