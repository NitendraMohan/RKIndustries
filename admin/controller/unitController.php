<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
require_once 'usersLogController.php';
//lodar record inside table
$db = new dbConnector();
$username = checkUserSession();
// if(!isset($username)){
//     return;
// }

if ($_POST['action'] == "load") {
    try {
        $sql = "SELECT * FROM tbl_unit";
        $result = $db->readData($sql);
        $sr = 1;
        $output = "";
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["id"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                        <td>
                            <button class='btn btn-success unitEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} ><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning unitDelete' data-id={$row["id"]}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
        // while ($row = $result->fetch(PDO::FETCH_ASSOC)) {


        // }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
//End

//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        $unit = strtoupper($_POST['unitname']);
        $ustatus = $_POST['status'];
        $sql = "select * from tbl_unit where unit=:unit";
        $params = ['unit' => $unit];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_unit(unit,status) values(:unit,:status)";
            $params = ['unit' => $unit, 'status' => $ustatus];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_unit", $newRecordId, $_SESSION["username"]);
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false));
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
        $sql = "select unit,status from tbl_unit where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_unit where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_unit", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_unit where id  = {$id}";
        $row = $db->readSingleRecord($sql);
        $output1 .= "<div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                <h4 class='modal-title'>Update Unit</h4>
            </div>
            <form action='' method='post' id='unitFormUpdata'>
                <div class='modal-body'>
                    <input type='hidden' id='unitId' name='id' value='{$row['id']}' />
                    <div class='form-group'>
                        <label for='unitname'>Unit</label>
                        <input class='form-control type='text' id='editunitname' name='unitname' value='{$row['unit']}'>
                     </div>
                     <div class='form-group'>
                        <label for='Status'>Status</label>
                        <select class='form-control' name='status' id='editstatus'>";
                        $options = array('', '0', '1');
                        $values = array('Select', 'Inactive', 'Active');
                        for ($i = 0; $i < count($options); $i++) {
                            $output1 .= '<option ' . ($row['status'] == $options[$i] ? 'selected="selected"' : '') . '>' . $values[$i] . '</option>';
                        }
                        $output1 .= " </select></div>
            <!-- Modal footer -->
                <div class='modal-footer'>
                    <button type='submit' class='btn btn-primary btnUpdate' id='btnUpdate' data-id='update'>Update</button>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                </div>
            </form>
            <div id='msg1'></div>
        </div>
    </div>
    </div>";
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output1;
}
//End

//Update record in database
if ($_POST['action'] == "update") {
    try {
        $id = $_POST['id'];
        $_POST['status'];

        $status = $_POST['status'] == 'Active' ?? 'Active' ?? 'Inactive';
        //get old record for user log
        $sql = "select unit,status from tbl_unit where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);

        $sql = "update tbl_unit set unit =:unit, status=:status where id=:id";
        $params = ['unit' => $_POST['unit'], 'status' =>  $status, 'id' => $id];
        $recordId = $db->ManageData($sql, $params);
        // echo json_encode(array("success"=>true,"msg"=>$recordId));
        // exit;
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_unit", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
            // echo json_encode(array('success' => true));
            echo json_encode(array("success" => true, "msg" => "Update successful: Your record has been successfully updated."));
        } else {
            echo json_encode(array("success" => false, "msg" => "Error! Record not updated"));
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
        $sql = "SELECT * FROM tbl_unit where unit like '%{$search_value}%'";
        $result = $db->readData($sql);
        print_r($result);
        // $result = $conn->query($sql);
        $sr = 1;
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["id"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
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
