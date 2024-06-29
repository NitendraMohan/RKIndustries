<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
require_once 'usersLogController.php';
//lodar record inside table
$db = new dbConnector();
$username = checkUserSession();
// if(!isset($productname)){
//     return;
// }

if ($_POST['action'] == "load") {
    try {
        $sql = "select p.*,v.vendor_name from tbl_purchase as p JOIN tbl_vendors as v ON p.vendorid=v.id";
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
                        <td>{$row["billno"]}</td>
                        <td>{$row["vendor_name"]}</td>
                        <td>{$row["cost"]}</td>
                        <td>{$row["tax_amount"]}</td>
                        <td>{$row["total_cost"]}</td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='tbl_purchase' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='tbl_purchase' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            <button class='btn btn-info btn-sm purchaseitem' title='Purchase Items' data-id={$row["billno"]}><i class='fa fa-chevron-right aria-hidden='true'>‌</i></button>
                            <button class='btn btn-primary btn-sm charges' title='Other Charges' data-id={$row["id"]}><i class='fa fa-inr' aria-hidden='true'></i></button>
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

if($_POST['action'] == "show_material"){
    header('Location: /employeeinfo-organization?'.$_SERVER['QUERY_STRING']);
      die();
}
//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        // print_r($_POST);
        // die();
        
        // $bomname = strtoupper($_POST['bomname']);
        // $categoryid = $_POST['category'];
        // $subcategoryid = $_POST['subcategory'];
        // $productid = $_POST['product'];
        // $brandid = $_POST['brand'];
        // $unitid = $_POST['unit'];
        // $qty = $_POST['qty'];
        // $detail = $_POST['detail'];
        // $ustatus = $_POST['status'];
        $sql = "select id from tbl_purchase where billno=:billno";
        $params = ['billno' => $_POST['billNumberName']];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_purchase(compid,billno,vendorid,cost,tax_amount,total_cost) values((select id from company_master),:billno,:vendorid,:cost,:tax_amount,:total_cost)";
            $params = [ 'billno' => $_POST['billNumberName'],'vendorid' => $_POST['vendorName'],'cost' => $_POST['costName'],'tax_amount' => $_POST['taxName'],'total_cost' => $_POST['totalCostName']];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_purchase", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_purchase where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_purchase where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_purchase", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_purchase where id  = {$id}";
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
        // print_r($_POST);
        // die();
        $id = $_POST['purchaseHiddenName'];
        //get old record for user log
        $sql = "select * from tbl_purchase where id=:id";
        $params = ["id" => $_POST["purchaseHiddenName"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "select id from tbl_purchase where billno=:billno && id != :id";
        $params = ['billno' => $_POST['billNumberName'], "id" => $_POST["purchaseHiddenName"]];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "update tbl_purchase set id=:id, billno=:billno, vendorid=:vendorid, cost=:cost, tax_amount=:tax_amount, total_cost=:total_cost where id=:id";
            $params = ['id'=>$id, 'billno' => $_POST['billNumberName'], 'vendorid' => $_POST['vendorName'],'cost' => $_POST['costName'],'tax_amount' => $_POST['taxName'], 'total_cost' => $_POST['totalCostName']];
            $recordId = $db->ManageData($sql, $params);
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_purchase", $_POST['purchaseHiddenName'], $_SESSION["username"], json_encode($oldRecord));
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
        // $conn = new PDO($this->dsn, $this->productname, $this->password);
        $sql = "select p.*,v.vendor_name from tbl_purchase as p JOIN tbl_vendors as v ON p.vendorid=v.id
                        where p.billno like '%{$search_value}%' or v.vendor_name like '%{$search_value}%' or p.cost like '%{$search_value}%'";        if($statusSearch!=''){
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
                        <td>{$row["billno"]}</td>
                        <td>{$row["vendor_name"]}</td>
                        <td>{$row["cost"]}</td>
                        <td>{$row["tax_amount"]}</td>
                        <td>{$row["total_cost"]}</td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='active' data-dbtable='bom_product' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' {$permissions['status']} data-id={$row['id']} data-status='deactive' data-dbtable='bom_product' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            <button class='btn btn-info btn-sm material' title='Materials' data-id={$row["id"]}><i class='fa fa-chevron-right aria-hidden='true'>‌</i></button>
                            <button class='btn btn-primary btn-sm material' title='Other Charges' data-id={$row["id"]}><i class='fa fa-inr' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
