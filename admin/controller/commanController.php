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

// fetchCategoryList($db,"category");
// fetchCategoryList($db,"unit");

// // Function to fetch category list based on user input
// function fetchCategoryList($db, $table) {
    // Check if action is categorylist
    if(isset($_POST['action']) && $_POST['action'] == "list"){
        // Check if category is set in POST data
        if(isset($_POST['category'])) {
            $category = $_POST['category'];
            $table = $_POST['tablename'];
            $field = $_POST['tablefield'];
            $sql = "SELECT DISTINCT {$field} FROM {$table} WHERE {$field} LIKE '%$category%'";
            $categorys = $db->readData($sql);
            $output ="";
            // Check if categories are retrieved
            if(isset($categorys)) {
                $output = "<ul style='list-style-type: none;'>";
                foreach($categorys as $category) {
                            //    "<li>{$result[$table.'_name']}</li>";
                    $output .= "<li>{$category[$field]}</li>";
                }
                $output .= "</ul>";
            } else {
                $output = "Error: Please select a valid {$table}";
            }
            // Output the category list
            echo $output;
        } else {
            echo "Error: Category parameter not set";
        }
    } else {
        echo "Error: Invalid action";
    }
// }


// Call the function with the database object ($db) as an argument
// fetchCategoryList($db);


?>