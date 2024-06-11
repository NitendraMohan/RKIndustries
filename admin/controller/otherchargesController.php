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
        $sql = "SELECT * FROM tbl_other_charges";
        $result = $db->readData($sql);
        $sr = 1;
        $output = "";
        if ($result) {
            foreach ($result as $row) {
                $checked = $row['is_percentage'] ==1 ?'Yes':'No';
                $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["expanse_name"]}</td>
                        <td>{$checked}</td>
                        <td>{$row["value"]}</td>
                        <td>{$row["detail"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_other_charges' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_other_charges' style='width:70px;'>Deactive</button>") . "</td>
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
//         $sql = "UPDATE tbl_other_charges SET status = 1 WHERE id = :id";
//     } elseif($action == 'Deactive'){
//         $sql = "UPDATE tbl_other_charges SET status = 0 WHERE id = :id";
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
        // print_r($_POST);die();
        $expanse_name = strtoupper($_POST['expanse_name']);
        $ustatus = $_POST['status'];
        $is_percentage = $_POST['is_percentage'] =='on'?1:0;
        $sql = "select * from tbl_other_charges where expanse_name=:expanse_name";
        $params = ['expanse_name' => $expanse_name];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true ));
        } else {
            $sql = "insert into tbl_other_charges(compid,expanse_name,is_percentage,value,detail,status) values((select id from company_master),:expanse_name,:is_percentage,:value,:detail,:status)";
            $params = ['expanse_name' => $expanse_name, 'is_percentage' => $is_percentage,"value"=>$_POST['expanse_value'], 'detail'=>$_POST['detail'], 'status' => $ustatus];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_other_charges", $newRecordId, $_SESSION["username"]);
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
        $sql = "select expanse_name,status from tbl_other_charges where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_other_charges where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_other_charges", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_other_charges where id  = {$id}";
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
        $id = $_POST['id'];
        $is_percentage = isset($_POST['is_percentage'])?1:0;
        // $status = $_POST['status'] == 'Active' ?? 'Active' ?? 'Inactive';
        //get old record for user log
        $sql = "select expanse_name,status from tbl_other_charges where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);

        $sql = "select * from tbl_other_charges where expanse_name=:expanse_name and id!={$id}";
        $params = ['expanse_name' => $_POST['expanse_name']];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "update tbl_other_charges set expanse_name =:expanse_name,is_percentage=:is_percentage,value=:value,detail=:detail, status=:status where id=:id";
            $params = ['expanse_name' => $_POST['expanse_name'],'is_percentage'=>$is_percentage,'value'=>$_POST['expanse_value'],'detail'=>$_POST['detail'], 'status' =>  $_POST['status'], 'id' => $id];
            $recordId = $db->ManageData($sql, $params);
            // echo json_encode(array("success"=>true,"msg"=>$recordId));
            // exit;
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_other_charges", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "SELECT * FROM tbl_other_charges where expanse_name like '%{$search_value}%'";
        $result = $db->readData($sql);
        print_r($result);
        // $result = $conn->query($sql);
        $sr = 1;
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["expanse_name"]}</td>
                        <td>{$row["is_percentage"]}</td>
                        <td>{$row["value"]}</td>
                        <td>{$row["detail"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_other_charges' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_other_charges' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                        <button class='btn btn-success expanse_nameEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} ><i class='fa fa-pencil' aria-hidden='true'></i></button>
                        <button class='btn btn-warning expanse_nameDelete' data-id={$row["id"]}><i class='fa fa-trash' aria-hidden='true'></i></button>
                        </td>
                        </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
