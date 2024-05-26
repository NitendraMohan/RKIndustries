<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
require_once 'usersLogController.php';
require_once (dirname(__FILE__) . '/../../config.php');
//lodar record inside table
$db = new dbConnector();
$username = checkUserSession();
// if(!isset($username)){
//     return;
// }
if ($_POST['action'] == "load") {
    try {
        $sql = "SELECT * FROM company_master";
        $result = $db->readSingleRecord($sql);
        $sr = 1;
        echo json_encode($result);
        
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
//End

//Insert or Update data into database
if ($_POST['action'] == "submit") {
    try {
        $targetDir = "";
        $targetFile = null;
        $saveRecord = true;
        if (isset($_FILES["logo"]) && $_FILES["logo"]["name"]!= "") {
            $targetDir = "../images/";
            $targetFile = $targetDir . $_FILES["logo"]["name"];
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            // Validation here
            if($_FILES["logo"]["name"]!=="") {
                if(move_uploaded_file($_FILES["logo"]["tmp_name"], $targetFile)){
                    $saveRecord = true;
                }
                else{
                    $saveRecord = false;
                    echo json_encode(array('success' => false,'msg'=>'Error File Path! Record not saved'));
                    exit;
                }
            }    
        }
        
        $sql = "select * from company_master";
        $result = $db->readSingleRecord($sql);
        if (isset($result)) {
            $sql = "update company_master set company_name=:company_name,gst_no=:gst_no,address=:address,contact_number=:contact_number,email=:email,logo=:logo where id=:id";
            $params = ['id'=>$result['id'],'company_name'=>$_POST['company_name'],'gst_no'=>$_POST['gst_no'],'address'=>$_POST['address'],'contact_number'=>$_POST['contact_number'],'email'=>$_POST['mail_id'],'logo'=>$targetFile ?? $result['logo']];
            $recordId = $db->ManageData($sql,$params);
            if($recordId){
                log_user_action($_SESSION['userid'], 'update', "company_master", $result['id'], $_SESSION["username"],json_encode($result));
                echo json_encode(array('success' => true, "msg"=>"Success! Record updated successfully"));
            }else {
                echo json_encode(array('success' => false,"msg"=>"Error! Record not saved"));
            }

        } else {
            $sql = "insert into company_master(company_name,gst_no,address,contact_number,email,logo) values(:company_name,:gst_no,:address,:contact_number,:email,:logo)";
            $params = ['company_name'=>$_POST['company_name'],'gst_no'=>$_POST['gst_no'],'address'=>$_POST['address'],'contact_number'=>$_POST['contact_number'],'email'=>$_POST['mail_id'],'logo'=>$$targetFile];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "company_master", $newRecordId, $_SESSION["username"]);
                echo json_encode(array('success' => true, "msg"=>"Success! Record saved successfully"));
            } else {
                echo json_encode(array('success' => false, "msg"=>"Error! Record not saved"));
            }
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End
