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
        $sql = "SELECT 
                    b.id, 
                    b.bom_name, 
                    p.product_name, 
                    br.brand_name,
                    COALESCE(bm.total_cost, 0) AS mcost,
                    COALESCE(oc.total_charge, 0) AS ocost, 
                    (COALESCE(bm.total_cost, 0) + COALESCE(oc.total_charge, 0)) AS total_cost,
                    u.unit, 
                    b.qty,
                    b.detail, 
                    b.image, 
                    b.status
                FROM 
                    tbl_bom_product b
                INNER JOIN 
                    tbl_products p ON b.product_id = p.id
                INNER JOIN 
                    tbl_unit u ON b.unit_id = u.id
                INNER JOIN 
                    tbl_brand br ON b.brand_id = br.id
                LEFT JOIN 
                    (SELECT bom_id,status, SUM(cost) AS total_cost FROM tbl_bom_material GROUP BY bom_id,status having status=1) bm ON b.id = bm.bom_id
                LEFT JOIN 
                    (SELECT bom_id,status, SUM(charge_value) AS total_charge FROM bom_other_charges GROUP BY bom_id,status having status=1) oc ON b.id = oc.bom_id
                WHERE 
                    b.id = {$_POST['bomid']}
                GROUP BY 
                    b.id, b.bom_name, p.product_name, br.brand_name, u.unit, b.qty, b.detail, b.image, b.status;";
        $bomdata = $db->readSingleRecord($sql);
        if (isset($bomdata)) {
            $result['bom_data'] = $bomdata;
            $rowCounts = count($bomdata);
            $sql = "SELECT oc.id as charge_id, oc.expanse_name, bom.id,
            COALESCE(bom.is_percentage, 0) AS is_percentage, 
            COALESCE(bom.apply_on_material, 0) AS apply_on_material,
            COALESCE(bom.charge_value, 0) AS charge_value,
            COALESCE(bom.status, 0) AS status 
            FROM tbl_other_charges oc JOIN bom_other_charges bom 
            ON oc.id = bom.charge_id";
            if(isset($_POST['bomid']) && $_POST['bomid']!=''){
                $sql .= " AND bom.bom_id = '{$_POST['bomid']}'";
            }
            else{
                $sql .= " AND bom.bom_id = ''";
                $disable_all_checkboxes = 'disabled';
            }
            $sql .=" order by oc.expanse_name";
            $chargesData = $db->readData($sql);
            $sr = 1;
            $output = "";
            if(isset($chargesData)){
                foreach ($chargesData as $row) {
                    // $insert_checked = $row['is_applicable']==1?'Yes':'No';
                    $is_percentage = $row['is_percentage']==1?'Yes':'No';
                    $apply_on_material = $row['apply_on_material']==1?'Yes':'No';
                    $output .= "<tr>
                                <input type='hidden' class='charge_id' value='{$row['charge_id']}'>
                                <td>{$sr}</td>
                                <td>{$row["expanse_name"]}</td>
                                <td>{$is_percentage}</td>
                                <td>{$apply_on_material}</td>
                                <td>{$row['charge_value']}</td>
                                <td>" . ($row['status'] == 1 ? 
                                "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='bom_other_charges' style='width:70px;'>Active</button>" 
                                : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='bom_other_charges' style='width:70px;'>Deactive</button>") . "</td>
                                <td>
                                <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                                <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} ><i class='fa fa-trash' aria-hidden='true'></i></button>
                                </td>
                                </tr>";
                    $sr++;
                }
            }
            // print_r($output);
            // die();
            $result['charges_data']=$output;
    }
        // while ($row = $result->fetch(PDO::FETCH_ASSOC)) {


        // }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($result);
}
//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        $saveRecord = true;
        $bomid = $_SESSION['bomid'];
        $chargeid= $_POST['expanse_name'];
        $ustatus = $_POST['status'];
        $is_percentage = isset($_POST['is_percentage'])?$_POST['is_percentage']:0;
        $apply_on_material = isset($_POST['apply_on_material'])?$_POST['apply_on_material']:0;
        $sql = "select id from bom_other_charges where bom_id=:bomid and charge_id=:chargeid";
        $params = ['bomid' => $bomid, 'chargeid' => $chargeid];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into bom_other_charges(compid,bom_id,charge_id,is_percentage,apply_on_material,charge_value,status) values((select id from company_master),:bom_id,:charge_id,:is_percentage,:apply_on_material,:charge_value,:status)";
            $params = [ 'bom_id' => $bomid,'charge_id'=>$chargeid, 'is_percentage' => $is_percentage,'apply_on_material' => $apply_on_material,'charge_value' => $_POST['charge_value'], 'status' => $_POST['status']];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "bom_other_charges", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from bom_other_charges where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from bom_other_charges where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "bom_other_charges", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from bom_other_charges where id  = {$id}";
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
        // print_r($_POST);die();
        $saveRecord = true;
        $id = $_POST['modalid'];
        $bomid = $_SESSION['bomid'];
        $chargeid = $_POST['expanse_name'];
        $is_percentage = isset($_POST['is_percentage'])?$_POST['is_percentage']:0;
        $apply_on_material = isset($_POST['apply_on_material'])?$_POST['apply_on_material']:0;
        //get old record for user log
        $sql = "select * from bom_other_charges where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "select id from bom_other_charges where bom_id=:bomid and charge_id=:chargeid and id!={$id}";
        $params = ['bomid' => $bomid, 'chargeid' => $chargeid];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "update bom_other_charges set charge_id=:charge_id, is_percentage=:is_percentage, apply_on_material=:apply_on_material, charge_value=:charge_value where id=:id";
            $params = ['id'=>$id, 'charge_id' => $chargeid, 'is_percentage' => $is_percentage, 'apply_on_material' => $apply_on_material, 'charge_value' =>$_POST['charge_value']];
            // echo $sql;
            // print_r($params);die;
            $recordId = $db->ManageData($sql, $params);
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "bom_other_charges", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
                echo json_encode(array("success" => true, "msg" => "Success: record updated successfully."));
            } else {
                echo json_encode(array("success" => false, "msg" => "Record not updated"));
            }
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}
//End
//End
// if ($_POST['action'] == "checkbox_submit") {
//     // print_r($_POST);
//     // die();
//     $params =[];
//     $bomid = $_POST['bom_id'];
//     $chargeid = $_POST['charge_id'];
//     $sql = "SELECT id FROM bom_other_charges WHERE bom_id=:bomid and charge_id=:chargeid";
//     $params = ['bomid'=>$bomid,'chargeid'=>$chargeid];
//     $record_id = $db->getID($sql, $params);
//     $params =[];
//     if($record_id>0){
//         $sql = "update bom_other_charges set 
//         is_applicable=:is_applicable, 
//         is_percentage=:is_percentage, 
//         apply_on_material=:apply_on_material 
//         where id=:id";
//         $params['id'] = $record_id;
//         $params['is_applicable']=$_POST['is_applicable'];
//         $params['is_percentage']=$_POST['is_percentage'];
//         $params['apply_on_material']=$_POST['apply_on_material'];
//         $params['charge_value']=$_POST['charge_value'];
//         $db->ManageData($sql, $params);
//     }
//     else{
//         $sql = "insert into bom_other_charges
//         (compid,
//         bom_id,
//         charge_id,
//         is_applicable,
//         is_percentage,
//         apply_on_material,
//         charge_value,
//         status) 
//         values
//         ((select id from company_master),
//         :bomid,
//         :chargeid,
//         :is_applicable,
//         :is_percentage,
//         :apply_on_material,
//         :charge_value,
//         :status)";
//         // echo $sql;
//         $params['bomid']=$bomid;
//         $params['chargeid']=$chargeid;
//         $params['is_applicable']=$_POST['is_applicable'];
//         $params['is_percentage']=$_POST['is_percentage'];
//         $params['apply_on_material']=$_POST['apply_on_material'];
//         $params['charge_value']=$_POST['charge_value'];
//         $params['status']=1;
//         // print_r($params);die();
//         $newRecordId = $db->insertData($sql, $params);
//     }

// }
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
        $sql = "SELECT * FROM tbl_user_permissions where username like '%{$search_value}%' or role like '%{$search_value}%' or gender like '%{$search_value}%' or mobile like '%{$search_value}%' or address like '%{$search_value}%' or email like '%{$search_value}%'";
        if($statusSearch!=''){
            $sql.="or status={$statusSearch}";
        }
        $result = $db->readData($sql);
        // print_r($result);
        // $result = $conn->query($sql);
        $sr = 1;
        foreach ($result as $row) {
            $output .= "<tr>
            <input type='hidden' class='module_id' value='{$row['module_id']}'>
            <td>{$sr}</td>
            <td>{$row["module_name"]}</td>
            <td><input type='checkbox' class='chkBox' id='insert' value='{$row['insert_record']}' {$disable_all_checkboxes} {$insert_checked} /></td>
            <td><input type='checkbox' class='chkBox' id='update' value='{$row['update_record']}' {$disable_all_checkboxes} {$update_checked} /></td>
            <td><input type='checkbox' class='chkBox' id='delete' value='{$row['delete_record']}' {$disable_all_checkboxes} {$delete_checked} /></td>
            <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
            </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
if($_POST['action']=="update_totalcost"){
    $bomid = $_POST['bomid'];
    $total_cost = $_POST['total_cost'];
    $sql = "update tbl_bom_product set bom_cost={$total_cost} where id={$bomid}";
    $recordId = $db->ManageData($sql);
    echo $recordId ?  1 :  0;
    
}
