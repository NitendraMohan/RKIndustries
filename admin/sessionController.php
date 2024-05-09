<?php
require_once '../admin/connection.inc.php';
require_once '../admin/utility/sessions.php';
$username = checkUserSession();
// echo $username;
// print_r($_POST);
// die();
if(isset($username)){
    $db = new dbConnector();
    if($_POST['action'] =="create"){
    $sql = "insert into financial_years(year_from,year_to,status) values(:year_from,:year_to,:status)";
    $params = ["year_from"=>$_POST["year_from"],"year_to"=>$_POST["year_to"],"status"=>$_POST["status"]];
    $newRecordId = $db->insertData($sql,$params);
    if(!empty($newRecordId)){
        $sql = "select * from financial_years where id=$newRecordId";
        $row = $db->readSingleRecord($sql);
    }
    $totalRecords = $db->CountData("select count(*) as total_records from financial_years");

    if(!empty($row)){
        $jsonArray = json_encode(($row)); ?>
        <tr>
            <td class="serial"><?php echo $totalRecords."." ?></td>
            <td class="id" style="display:none;"> <?php echo $row["id"]?> </td>
            <td class="year_from"> <span class="name"><?php echo $row["year_from"]?></span> </td>
            <td class="year_to"> <span class="product"><?php echo $row["year_to"]?></span> </td>
            <td class="status"><span class="name">
                <?php echo $row["status"]==1
                ?"<a href='?type=status&operation=deactive&id=".$row['id']."'>Active</a>"
                :"<a href='?type=status&operation=active&id=".$row['id']."'>Inactive</a>"
                ?></span></td>
            <td>
                <button class="edit btn btn-success"   data-toggle="modal" data-target="#myModal" onclick=setModelValues('<?php echo $jsonArray;?>')><i class="fa fa-pencil" aria-hidden="true"></i></button>
                <!-- <button class="save btn btn-success" style="display:none;"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button class="cancel btn btn-danger" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button> -->
                <button class="del btn btn-warning" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
            </td>
        </tr> 
   <?php } 
   } 
//    print_r($_POST);die();
   if($_POST['action'] =="del"){
    // echo 'delete action received';die();
    $sql = "delete from financial_years where id=:id";
    $params = ["id"=>$_POST["id"]];
    $RecordId = $db->ManageData($sql,$params);
    echo $RecordId;
   }
   if($_POST['action'] =="update"){
    // echo 'delete action received';die();
    $sql = "update financial_years set year_from=:year_from,year_to=:year_to,status=:status where id=:id";
    $params = ["id"=>$_POST["id"],"year_from"=>$_POST["year_from"],"year_to"=>$_POST["year_to"],"status"=>$_POST["status"]];
    $RecordId = $db->ManageData($sql,$params);
    echo $RecordId;
   } ?> 
<?php } ?>