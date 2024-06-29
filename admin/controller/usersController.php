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
        // $sql = "SELECT * FROM tbl_users";
        $sql = "select u.*,d.dept_name,dg.designation_name from tbl_users as u 
        JOIN tbl_deparment as d ON u.dept_id = d.id
        JOIN tbl_designation as dg ON u.designation_id=dg.id";
        $result = $db->readData($sql);
        if (isset($result)) {
            $rowCounts = count($result);
            $params = ['userid' => $_SESSION['userid'], 'moduleid' => $_SESSION['moduleid']];
            $permissions = $db->get_buttons_permissions($params);
            $sr = 1;
            $output = "";
            foreach ($result as $row) {
                $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["username"]}</td>
                        <td>{$row["role"]}</td>
                        <td>{$row["dept_name"]}</td>
                        <td>{$row["designation_name"]}</td>
                        <td>{$row["gender"]}</td>
                        <td>{$row["dob"]}</td>
                        <td>{$row["mobile"]}</td>
                        <td>{$row["email"]}</td>
                        <td>{$row["address"]}</td>
                        <td><img src='{$row["image"]}' class='img-circle' height='40px' width='auto' /></td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='tbl_users' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='tbl_users' style='width:70px;'>Deactive</button>") . "</td>
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
        // print_r($_POST);
        $targetFile = null;
        $saveRecord = true;
        if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
            $targetDir = "../images/";
            $targetFile = $targetDir . $_FILES["image"]["name"];
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            // Validation here
            if ($_FILES["image"]["name"] !== "") {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $saveRecord = true;
                } else {
                    $saveRecord = false;
                    echo json_encode(array('success' => false, 'msg' => 'Error File Path! Record not saved'));
                    exit;
                }
            }
        }
        $username = strtoupper($_POST['username']);
        // $ustatus = $_POST['status'];
        $sql = "select mobile from tbl_users where username=:username";
        $params = ['username' => $username];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_users(compid,role,dept_id,designation_id,username,gender,dob,mobile,email,address,image,password,status) values((select id from company_master),:role,:deptid,:designation_Id,:username,:gender,:dob,:mobile,:email,:address,:image,:password,:status)";
            $params = ['role' => $_POST['role'], 'deptid' => $_POST['departmentname'], 'designation_Id' => $_POST['designation'], 'username' => $username, 'gender' => $_POST['gender'], 'dob' => $_POST['dob'], 'mobile' => $_POST['mobile'], 'email' => $_POST['email'], 'address' => $_POST['address'], 'image' => $targetFile ?? '../images/favicon.png', 'password' => $_POST['password'], 'status' => 1];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_users", $newRecordId, $_SESSION["username"]);
                echo json_encode(array('success' => true, 'msg' => 'Success! New record added successfully'));
            } else {
                echo json_encode(array('success' => false, 'msg' => 'Error! New record not added'));
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
        $sql = "select * from tbl_users where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_users where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_users", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_users where id  = {$id}";
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
        $targetFile = "";
        $saveRecord = true;
        if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
            $targetDir = "../images/";
            $targetFile = $targetDir . $_FILES["image"]["name"];
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            // Validation here
            if ($_FILES["image"]["name"] !== "") {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $saveRecord = true;
                } else {
                    $saveRecord = false;
                    echo json_encode(array('success' => false, 'msg' => 'Error File Path! Record not saved'));
                    exit;
                }
            }
        } else if (isset($_POST['image']) && $_POST['image'] != '') {
            // echo $_POST['image'];
            $targetFile = $_POST['image'];
        }
        $id = $_POST['userHiddenName'];
        //get old record for user log
        $sql = "select * from tbl_users where id=:id";
        $params = ["id" => $_POST["userHiddenName"]];
        $oldRecord = $db->readSingleRecord($sql, $params);

        $sql = "update tbl_users set role=:role,dept_id=:deptid,designation_id=:designation_Id,username=:username,gender=:gender,dob=:dob,mobile=:mobile,email=:email,address=:address,image=:image,password=:password where id=:id";
        $params = ['id' => $id, 'deptid' => $_POST['departmentname'], 'designation_Id' => $_POST['designation'], 'role' => $_POST['role'], 'username' => strtoupper($_POST['username']), 'gender' => $_POST['gender'], 'dob' => $_POST['dob'], 'mobile' => $_POST['mobile'], 'email' => $_POST['email'], 'address' => $_POST['address'], 'password' => $_POST['password'], 'image' => $targetFile];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_users", $_POST['userHiddenName'], $_SESSION["username"], json_encode($oldRecord));
            echo json_encode(array("success" => true, "msg" => "Success: record updated successfully."));
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
        $statusSearch = '';
        if ($search_value == 'active') {
            $statusSearch = 1;
        } elseif ($search_value == 'inactive') {
            $statusSearch = 0;
        }
        // $conn = new PDO($this->dsn, $this->username, $this->password);
        $sql = "select u.*,d.dept_name,dg.designation_name from tbl_users as u 
        JOIN tbl_deparment as d ON u.dept_id = d.id
        JOIN tbl_designation as dg ON u.designation_id=dg.id 
        where u.username like '%{$search_value}%' 
        or d.dept_name like '%{$search_value}%' 
        or dg.designation_name like '%{$search_value}%' 
        or u.role like '%{$search_value}%' 
        or u.gender like '%{$search_value}%' 
        or u.mobile like '%{$search_value}%' 
        or u.address like '%{$search_value}%' 
        or u. email like '%{$search_value}%'";
        if ($statusSearch != '') {
            $sql .= "or status={$statusSearch}";
        }
        $result = $db->readData($sql);
        if (isset($result)) {
            $params = ['userid' => $_SESSION['userid'], 'moduleid' => $_SESSION['moduleid']];
            $permissions = $db->get_buttons_permissions($params);
            $sr = 1;
            foreach ($result as $row) {
                $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["username"]}</td>
                        <td>{$row["role"]}</td>
                        <td>{$row["dept_name"]}</td>
                        <td>{$row["designation_name"]}</td>
                        <td>{$row["gender"]}</td>
                        <td>{$row["dob"]}</td>
                        <td>{$row["mobile"]}</td>
                        <td>{$row["email"]}</td>
                        <td>{$row["address"]}</td>
                        <td><img src='{$row["image"]}' height='40px' width='auto'/></td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='tbl_users' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='tbl_users' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                        <button class='btn btn-success unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']} ><i class='fa fa-pencil' aria-hidden='true'></i></button>
                        <button class='btn btn-warning unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                        </td>
                        </tr>";
                $sr++;
            }
        } else {
            $output =   "<tr>
                            <td colspan = '10'><h4><span style='color:red;'>Attention:</span> The record cannot be located using the provided value.</h4></td>
                        </tr>";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
