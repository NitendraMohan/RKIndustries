<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
require_once 'usersLogController.php';
//lodar record inside table
$db = new dbConnector();
$username = checkUserSession();
$params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);
// print_r($_POST);
       
if ($_POST['action'] == "load") {
    try {
        $sql = "SELECT * FROM tbl_designation";
        $result = $db->readData($sql);
        $sr = 1;
        $output = "";
        if ($result) {
            foreach ($result as $row) {
                $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["designation_name"]}</td>
                        
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_designation' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_designation' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                           <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                         </td>
                        </tr>";
                $sr++;
            }
        } else {
            echo "No record found";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
//End

// if(isset($_POST['action']) && isset($_POST['id'])){
//     $action = $_POST['action'];
//     $id = $_POST['id'];
    
//     // Prepare the SQL statement
//     if($action == 'Active'){
//         $sql = "UPDATE tbl_designation SET status = 1 WHERE id = :id";
//     } elseif($action == 'Deactive'){
//         $sql = "UPDATE tbl_designation SET status = 0 WHERE id = :id";
//     }

//     $params = ['id' => $id];
//     $result = $db->ManageData($sql, $params);
    
//     // Return appropriate response
//     if($result){
//         echo 1;
//     } else{
//         echo 0;
//     }
// }

//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        // print_r($_POST);
        // die();  
        $designation = strtoupper($_POST['designationName']);
        $sql = "select * from tbl_designation where designation_name=:designationName";
        $params = ['designationName' => $designation];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true ));
        } else {
            $sql = "insert into tbl_designation(designation_name,status) values(:designation,:status)";
            $params = ['designation' => $designation, 'status' => 1];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_designation", $newRecordId, $_SESSION["username"]);
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
        $sql = "select designation_name,status from tbl_designation where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_designation where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_designation", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_designation where id  = {$id}";
        $row = $db->readSingleRecord($sql);
        echo json_encode($row);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End

//Update record in database
if ($_POST['action'] == "update") {
    try {
        // print_r($_POST);
        // die();
        $designation_name = strtoupper($_POST['designationName']);
        $id = $_POST['designationHiddenName'];
        // $status = $_POST['status'] == 'Active' ?? 'Active' ?? 'Inactive';
        //get old record for user log
        $sql = "select designation_name,status from tbl_designation where id=:id";
        $params = ["id" => $id];
        $oldRecord = $db->readSingleRecord($sql, $params);

        $sql = "select * from tbl_designation where designation_name=:designation_name and id!={$id}";
        $params = ['designation_name' => $_POST['designationName']];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "update tbl_designation set designation_name =:designation_name where id=:id";
            $params = ['designation_name' => $designation_name, 'id' => $id];
            $recordId = $db->ManageData($sql, $params);
            // echo json_encode(array("success"=>true,"msg"=>$recordId));
            // exit;
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_designation", $id, $_SESSION["username"], json_encode($oldRecord));
                // echo json_encode(array('success' => true));
                echo json_encode(array("success" => true, "msg" => "Update successful: Your record has been successfully updated."));
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
        // $conn = new PDO($this->dsn, $this->username, $this->password);
        $sql = "SELECT * FROM tbl_designation where designation_name like '%{$search_value}%'";
        $result = $db->readData($sql);
        print_r($result);
        // $result = $conn->query($sql);
        $sr = 1;
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["designation_name"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_designation' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_designation' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                        <button class='btn btn-success unitEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} ><i class='fa fa-pencil' aria-hidden='true'></i></button>
                        <button class='btn btn-warning unitDelete' data-id={$row["id"]}><i class='fa fa-trash' aria-hidden='true'></i></button>
                        </td>
                        </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
