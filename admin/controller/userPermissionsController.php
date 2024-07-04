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
        $disable_all_checkboxes = '';
        $sql = "SELECT m.id as module_id, m.module_name, COALESCE(p.insert_record, 0) AS insert_record, COALESCE(p.update_record, 0) AS update_record, COALESCE(p.delete_record, 0) AS delete_record,COALESCE(p.status, 0) AS status FROM tbl_modules m LEFT JOIN tbl_user_permissions p ON m.id = p.moduleid";
        if(isset($_POST['userid']) && $_POST['userid']!=''){
            $sql .= " AND p.userid = '{$_POST['userid']}'";
        }
        else{
            $sql .= " AND p.userid = ''";
            $disable_all_checkboxes = 'disabled';
        }
        $sql .=" order by m.module_name";
        $result = $db->readData($sql);
        $rowCounts = count($result);
        $sr = 1;
        $output = "";
        foreach ($result as $row) {
            $insert_checked = $row['insert_record']==1?'checked':'';
            $update_checked = $row['update_record']==1?'checked':'';
            $delete_checked = $row['delete_record']==1?'checked':'';
            $status_checked = $row['status']==1?'checked':'';
            $output .= "<tr>
                        <input type='hidden' class='module_id' value='{$row['module_id']}'>
                        <td>{$sr}</td>
                        <td>{$row["module_name"]}</td>
                        <td><input type='checkbox' class='chkBox' id='insert' value='{$row['insert_record']}' {$disable_all_checkboxes} {$insert_checked} /></td>
                        <td><input type='checkbox' class='chkBox' id='update' value='{$row['update_record']}' {$disable_all_checkboxes} {$update_checked} /></td>
                        <td><input type='checkbox' class='chkBox' id='delete' value='{$row['delete_record']}' {$disable_all_checkboxes} {$delete_checked} /></td>
                        <td><input type='checkbox' class='chkBox' id='status' value='{$row['status']}' {$disable_all_checkboxes} {$status_checked} /></td>
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
if ($_POST['action'] == "checkbox_submit") {
    $params =[];
    $userid = $_POST['userid'];
    $moduleid = $_POST['moduleid'];
    $sql = "SELECT id FROM tbl_user_permissions WHERE userid=:userid and moduleid=:moduleid";
    $params = ['userid'=>$userid,'moduleid'=>$moduleid];
    $record_id = $db->getID($sql, $params);
    $params =[];
    if($record_id>0){
        $sql = "update tbl_user_permissions set 
        insert_record=:insert_record, 
        update_record=:update_record, 
        delete_record=:delete_record,
        status=:status  
        where id=:id";
        $params['id'] = $record_id;
        $params['insert_record']=$_POST['insertstatus'];
        $params['update_record']=$_POST['updatestatus'];
        $params['delete_record']=$_POST['deletestatus'];
        $params['status']=$_POST['status'];
        $db->ManageData($sql, $params);
    }
    else{
        $sql = "insert into tbl_user_permissions
        (compid,
        userid,
        moduleid,
        insert_record,
        update_record,
        delete_record,
        status) 
        values
        ((select id from company_master),
        :userid,
        :moduleid,
        :insert_record,
        :update_record,
        :delete_record,
        :status)";
        // echo $sql;
        $params['userid']=$userid;
        $params['moduleid']=$moduleid;
        $params['insert_record']=$_POST['insertstatus'];
        $params['update_record']=$_POST['updatestatus'];
        $params['delete_record']=$_POST['deletestatus'];
        $params['status']=$_POST['status'];
        $newRecordId = $db->insertData($sql, $params);
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
