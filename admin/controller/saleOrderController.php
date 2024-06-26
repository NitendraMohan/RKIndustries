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
        // $sql = "SELECT * FROM tbl_sale_order";
        $sql = "select u.*,d.party_name from tbl_sale_order as u 
        JOIN tbl_parties as d ON u.party_id = d.id";
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
                        <td>{$row["party_name"]}</td>
                        <td>{$row["bill_no"]}</td>
                        <td>{$row["voucher_no"]}</td>
                        <td>{$row["order_date"]}</td>
                        <td>{$row["delivery_date"]}</td>
                        <td>{$row["payment_mode"]}</td>
                        <td>{$row["delivery_address"]}</td>
                        <td>{$row["terms"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_sale_order' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_sale_order' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            <button class='btn btn-info btn-sm showProducts' title='Products' data-id={$row["id"]}><i class='fa fa-chevron-right aria-hidden='true'>‌</i></button>
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
        $bill_no = strtoupper($_POST['bill_no']);
        $sql = "select id from tbl_sale_order where bill_no=:bill_no";
        $params = ['bill_no' => $bill_no];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_sale_order(compid,party_id,bill_no,order_date,delivery_date,voucher_no,payment_mode,delivery_address,terms,other_detail,status) values((select id from company_master),:party_id,:bill_no,:order_date,:delivery_date,:voucher_no,:payment_mode,:delivery_address,:terms,:other_detail,:status)";
            $params = ['party_id'=>$_POST['party_id'],'bill_no'=>$_POST['bill_no'],'order_date'=>$_POST['order_date'],'delivery_date'=>$_POST['delivery_date'],'voucher_no'=>$_POST['voucher_no'],'payment_mode'=>$_POST['payment_mode'],'delivery_address'=>$_POST['delivery_address'],'terms'=>$_POST['terms'],'other_detail'=>$_POST['other_detail'],'status'=>1];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_sale_order", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_sale_order where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_sale_order where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_sale_order", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_sale_order where id  = {$id}";
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
        $id = $_POST['userHiddenName'];
        //get old record for user log
        $sql = "select * from tbl_sale_order where id=:id";
        $params = ["id" => $_POST["userHiddenName"]];
        $oldRecord = $db->readSingleRecord($sql, $params);

        $sql = "update tbl_sale_order set party_id=:party_id,bill_no=:bill_no,order_date=:order_date,delivery_date=:delivery_date,voucher_no=:voucher_no,payment_mode=:payment_mode,delivery_address=:delivery_address,terms=:terms,other_detail=:other_detail where id=:id";
        $params = ['id' => $id, 'party_id' => $_POST['party_id'], 'bill_no' => $_POST['bill_no'], 'order_date' => $_POST['order_date'], 'delivery_date' => $_POST['delivery_date'], 'voucher_no' => $_POST['voucher_no'], 'payment_mode' => $_POST['payment_mode'], 'delivery_address' => $_POST['delivery_address'], 'terms' => $_POST['terms'], 'other_detail' => $_POST['other_detail']];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_sale_order", $_POST['userHiddenName'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select u.*,d.party_name from tbl_sale_order as u 
        JOIN tbl_parties as d ON u.party_id = d.id
        where u.bill_no like '%{$search_value}%' 
        or d.party_name like '%{$search_value}%' 
        or u.voucher_no like '%{$search_value}%'";
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
                    <td>{$row["party_name"]}</td>
                        <td>{$row["bill_no"]}</td>
                        <td>{$row["voucher_no"]}</td>
                        <td>{$row["order_date"]}</td>
                        <td>{$row["delivery_date"]}</td>
                        <td>{$row["payment_mode"]}</td>
                        <td>{$row["delivery_address"]}</td>
                        <td>{$row["terms"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_sale_order' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_sale_order' style='width:70px;'>Deactive</button>") . "</td>
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
