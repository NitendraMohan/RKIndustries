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
        $sql = "SELECT * FROM tbl_users";
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
                        <td>{$row["username"]}</td>
                        <td>{$row["role"]}</td>
                        <td>{$row["gender"]}</td>
                        <td>{$row["dob"]}</td>
                        <td>{$row["mobile"]}</td>
                        <td>{$row["email"]}</td>
                        <td>{$row["address"]}</td>
                        <td><img src='{$row["image"]}' class='img-circle' height='40px' width='auto' /></td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
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
        $ustatus = $_POST['status'];
        $sql = "select mobile from tbl_users where username=:username";
        $params = ['username' => $username];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_users(compid,role,username,gender,dob,mobile,email,address,image,password,status) values((select id from company_master),:role,:username,:gender,:dob,:mobile,:email,:address,:image,:password,:status)";
            $params = ['role' => $_POST['role'], 'username' => $username, 'gender' => $_POST['gender'], 'dob' => $_POST['dob'], 'mobile' => $_POST['mobile'], 'email' => $_POST['email'], 'address' => $_POST['address'], 'image' => $targetFile ?? '../images/favicon.png', 'password' => $_POST['password'], 'status' => $_POST['status']];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_users", $newRecordId, $_SESSION["username"]);
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
        }
        else if(isset($_POST['image']) && $_POST['image']!=''){
            // echo $_POST['image'];
            $targetFile = $_POST['image'];
        }
        $id = $_POST['modalid'];
        //get old record for user log
        $sql = "select * from tbl_users where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        
        $sql = "update tbl_users set role=:role,username=:username,gender=:gender,dob=:dob,mobile=:mobile,email=:email,address=:address,image=:image,status=:status,password=:password where id=:id";
        $params = ['id'=>$id, 'role' => $_POST['role'], 'username' => $_POST['username'], 'gender' => $_POST['gender'], 'dob' => $_POST['dob'], 'mobile' => $_POST['mobile'], 'email' => $_POST['email'], 'address' => $_POST['address'],'password' => $_POST['password'], 'image' => $targetFile, 'status' => $_POST['status']];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_users", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
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
        if($search_value == 'active'){
            $statusSearch = 1;
        }
        elseif( $search_value == 'inactive' ){
            $statusSearch = 0;
        }
        // $conn = new PDO($this->dsn, $this->username, $this->password);
        $sql = "SELECT * FROM tbl_users where username like '%{$search_value}%' or role like '%{$search_value}%' or gender like '%{$search_value}%' or mobile like '%{$search_value}%' or address like '%{$search_value}%' or email like '%{$search_value}%'";
        if($statusSearch!=''){
            $sql.="or status={$statusSearch}";
        }
        $result = $db->readData($sql);
        // print_r($result);
        // $result = $conn->query($sql);
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        foreach ($result as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["username"]}</td>
                        <td>{$row["role"]}</td>
                        <td>{$row["gender"]}</td>
                        <td>{$row["dob"]}</td>
                        <td>{$row["mobile"]}</td>
                        <td>{$row["email"]}</td>
                        <td>{$row["address"]}</td>
                        <td><img src='{$row["image"]}' height='40px' width='auto'/></td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                        <td>
                        <button class='btn btn-success unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']} ><i class='fa fa-pencil' aria-hidden='true'></i></button>
                        <button class='btn btn-warning unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                        </td>
                        </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}