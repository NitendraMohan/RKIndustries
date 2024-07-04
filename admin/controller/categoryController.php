<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
require_once 'usersLogController.php';
//lodar record inside table
$db = new dbConnector();
$username = checkUserSession();
// if(!isset($categoryname)){
//     return;
// }

if ($_POST['action'] == "load") {
    try {
        $sql = "SELECT * FROM tbl_category";
        $result = $db->readData($sql);
        if (isset($result)) {
        $rowCounts = count($result);
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        $output = "";
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["category_name"]}</td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='tbl_category' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='tbl_category' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
    }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
//End

//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        $categoryname = strtoupper($_POST['categoryname']);
        $sql = "select id from tbl_category where category_name=:categoryname";
        $params = ['categoryname' => $categoryname];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_category(compid,category_name,status) values((select id from company_master),:categoryname,:status)";
            $params = [ 'categoryname' => $categoryname, 'status' => 1];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_category", $newRecordId, $_SESSION["username"]);
                echo json_encode(array('success' => true, 'msg'=>'Success! New record added successfully'));
            } else {
                echo json_encode(array('success' => false, 'msg'=>'Error! New record not added'));
            }
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End

//Delete data from database
if ($_POST['action'] == "delete") {
    try {
        $id = $_POST['id'];
        //get old record for user log
        $sql = "select * from tbl_category where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_category where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_category", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
            echo 1;
        } else {
            echo 0;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End 

//Model display with data for updation record
if ($_POST['action'] == "edit") {
    try {
        $output1 = '';
        $id = $_POST['id'];
        $sql = "select * from tbl_category where id  = {$id}";
        $row = $db->readSingleRecord($sql);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($row);
}
//End

//Update record in database
if ($_POST['action'] == "update") {
    try {
        $id = $_POST['categoryHiddenName'];
        //get old record for user log
        $sql = "select * from tbl_category where id=:id";
        $params = ["id" => $_POST["categoryHiddenName"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        
        $sql = "select * from tbl_category where category_name=:categoryName and id != :id";
        $params = ["id" => $id, "categoryName" => $_POST["categoryname"]];
        $res = $db->readSingleRecord($sql,$params);
        if($res){
            echo json_encode(array('duplicate' => true));     
        }else{
            
            $sql = "update tbl_category set category_name=:categoryname where id=:id";
            $params = ['id'=>$id, 'categoryname' => strtoupper($_POST['categoryname'])];
            $recordId = $db->ManageData($sql, $params);
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_category", $_POST['categoryHiddenName'], $_SESSION["username"], json_encode($oldRecord));
                echo json_encode(array("success" => true, "msg" => "Success: record updated successfully."));
            } else {
                echo json_encode(array("success" => false, "msg" => "Error! Record not updated"));
            }
        }
        
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End
if ($_POST['action'] == "search") {
    try {
        $output = "";
        $search_value = $_POST['search'];
        $statusSearch = '';
        if($search_value == 'active'){
            $statusSearch = 1;
        }
        elseif( $search_value == 'inactive' ){
            $statusSearch = 0;
        }
        $sql = "SELECT * FROM tbl_category where category_name like '%{$search_value}%'";
        if($statusSearch!=''){
            $sql.="or status={$statusSearch}";
        }
        $result = $db->readData($sql);
        if(isset($result)){
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        if(isset($result)) foreach ($result as $row) {
            $output .= "<tr>
            <td>{$sr}</td>
            <td>{$row["category_name"]}</td>
            <td>" . ($row['status'] == 1 
            ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='tbl_category' style='width:70px;'>Active</button>" 
            : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='tbl_category' style='width:70px;'>Deactive</button>") . "</td>
            <td>
                <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                </td>
            </tr>";
            $sr++;
        }
    }
    // else{
    //     $output =   "<tr>
    //     <td colspan = '10'><h4><span style='color:red;'>Attention:</span> The record cannot be located using the provided value.</h4></td>
    // </tr>";
    // }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
