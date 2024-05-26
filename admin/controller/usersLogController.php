<?php
require_once '../connection.inc.php';
$db = new dbConnector();
if ($_POST['action'] == "loadUserLogs") {
    try {
        $sql = "SELECT ua.*, u.user_name from user_actions_log ua join login u where ua.user_id=u.id";
        $result = $db->readData($sql);
        $sr = 1;
        foreach ($result as $row) {
            # code...
            $output .= "<tr>
                    <td>{$sr}</td>
                    <td>{$row["id"]}</td>
                    <td>{$row["user_name"]}</td>
                    <td>{$row["action"]} in {$row["table_name"]}</td>
                    <td>{$row["action_time"]}</td>
                    <td><button class='btn btn-info unitDelete' data-id={$row["id"]}><i class='fa fa-undo' aria-hidden='true'></i></button>
                    </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
//Delete data from database
if ($_POST['action'] == "undo") {
    undo_action_by_id($_POST['id']);
    // try {
    //     $id = $_POST['id'];
    //     $sql = "delete from tbl_unit where id = '{$id}'";
    //     if ($conn->query($sql)) {
    //         echo 1;
    //     } else {
    //         echo 0;
    //     }
    // } catch (PDOException $e) {
    //     echo "Connection failed: " . $e->getMessage();
    // }
}
//End 
function log_user_action($user_id, $action, $table_name, $record_id, $details = null, $previous_data = null) {
    $db = new dbConnector();
    $qry = "INSERT INTO user_actions_log (user_id, action, table_name, record_id, details, previous_data) VALUES (:user_id, :action, :table_name, :record_id, :details, :previous_data)";
    $params = ["user_id"=>$user_id, "action" => $action,"table_name"=>$table_name,"record_id"=>$record_id, "details"=> $details, "previous_data"=>$previous_data];
    $lastInsertedId = $db->insertData($qry, $params);  
}

function undo_action_by_id($log_id) {
    $db = new dbConnector();
        // Fetch the action log by ID
        $sql = "SELECT * FROM user_actions_log WHERE id = :id";
        $params = ["id"=>$log_id];
        $log = $db->readSingleRecord($sql, $params);
        if ($log) {
            $table_name = $log['table_name'];
            $record_id = $log['record_id'];
            $previous_data = json_decode($log['previous_data'], true);
            
            // Revert actions based on the log
            switch ($log['action']) {
                case 'create':
                    // Delete the created record
                    $sql = "DELETE FROM $table_name WHERE id = :id";
                    $params = ["id"=>$record_id];        
                    $db->ManageData($sql, $params);
                    break;
    
                case 'update':
                    // Restore the previous state
                    $set_clause = [];
                    foreach ($previous_data as $column => $value) {
                        $set_clause[] = "$column = '$value'";
                    }
                    $set_clause = implode(", ", $set_clause);
                    $sql = "UPDATE $table_name SET $set_clause WHERE id = :id";
                    $params = ["id"=>$record_id];        
                    $db->ManageData($sql, $params);
                    break;
    
                case 'delete':
                    // Re-insert the deleted record
                    $columns = implode(", ", array_keys($previous_data));
                    $values = implode(", ", array_map(function($value) { return "'$value'"; }, array_values($previous_data)));
                    $sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
                    $db->ManageData($sql);
                    break;
            }
    
            // mark the log entry as undone or delete it
            $sql = "DELETE FROM user_actions_log WHERE id = :id";
            $params = ["id"=>$log_id];
            $db->ManageData($sql,$params);
            echo 1;
        }
    }

?>