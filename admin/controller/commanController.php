<?php
require_once '../connection.inc.php';
$db = new dbConnector();
// print_r($_POST);
// die();
if(isset($_POST['action']) && isset($_POST['id'])){
    $action = $_POST['action'];
    $id = $_POST['id'];
    $dbtable = $_POST['dbtable'];
    
    // Prepare the SQL statement
    if($action == 'Active'){
        $sql = "UPDATE {$dbtable} SET status = 1 WHERE id = :id";
    } elseif($action == 'Deactive'){
        $sql = "UPDATE {$dbtable} SET status = 0 WHERE id = :id";
    }
// echo $sql;
// die();
    $params = ['id' => $id];
    $result = $db->ManageData($sql, $params);
    
    // Return appropriate response
    if($result){
        echo 1;
    } else{
        echo 0;
    }

}
?>