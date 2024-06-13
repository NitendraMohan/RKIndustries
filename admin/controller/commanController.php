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
    // if(isset($_POST['action']) && $_POST['action'] == "list"){
    //     // Check if category is set in POST data
    //     $table = $_POST['tablename'];
    //     $field = $_POST['tablefield'];
    //     if(isset($_POST['categoryId'])) {
    //         // Fetch subcategories based on the selected category ID
    //         $categoryId = $_POST['categoryId'];
    //         $subcategory = $_POST['query'];
    //         $sql = "SELECT DISTINCT id, {$field} FROM {$table} WHERE category_id = {$categoryId} AND {$field} LIKE '%$subcategory%'";
    //         $subcategories = $db->readData($sql);
    //         $output = "";
    
    //         if(isset($subcategories) && !empty($subcategories)) {
    //             $output = "<ul style='list-style-type: none;'>";
    //             foreach($subcategories as $subcategory) {
    //                 $output .= "<li data-id='{$subcategory['id']}'>{$subcategory[$field]}</li>";
    //             }
    //             $output .= "</ul>";
    //         } else {
    //             $output = "Error: No subcategories found";
    //         }
    
    //     }else if(isset($_POST['category'])) {
    //         $category = $_POST['category'];
           
    //         $sql = "SELECT  DISTINCT id, {$field} FROM {$table} WHERE {$field} LIKE '%$category%'";
    //         $categorys = $db->readData($sql);
    //         $output ="";
    //         // Check if categories are retrieved
    //         if(isset($categorys)) {
    //             $output = "<ul style='list-style-type: none;'>";
    //             foreach($categorys as $category) {
    //                         //    "<li>{$result[$table.'_name']}</li>";
    //                 // $output .= "<input type='hidden' name='category_id[]' value='{$category['id']}'>
    //                 //             <li>{$category[$field]}</li>";
    //                 $output .= "<li data-category-id='{$category['id']}'>{$category[$field]}</li>";
    //             }
    //             $output .= "</ul>";
    //         } else {
    //             $output = "Error: Please select a valid {$table}";
    //         }
    //         // Output the category list
    //         echo $output;
    //     } else {
    //         echo "Error: Category parameter not set";
    //     }
    // } 












    if(isset($_POST['action']) && $_POST['action'] == "list") {
        $table = $_POST['tablename'];
        $field = $_POST['tablefield'];
    
        if(isset($_POST['categoryId'])) {
            $categoryId = $_POST['categoryId'];
            $query = $_POST['query'];
            $sql = "SELECT DISTINCT id, {$field} FROM {$table} WHERE category_id = {$categoryId} AND {$field} LIKE '%$query%'";
            $items = $db->readData($sql);
        } else if(isset($_POST['searchValue'])) {
            $searchValue = $_POST['searchValue'];
            $sql = "SELECT DISTINCT id, {$field} FROM {$table} WHERE {$field} LIKE '%$searchValue%' and status = 1";
            $items = $db->readData($sql);
        } else {
            $items = [];
        }
    
        $output = "";
        if(isset($items) && !empty($items)) {
            $output = "<ul style='list-style-type: none;'>";
            foreach($items as $item) {
                $output .= "<li data-id='{$item['id']}'>{$item[$field]}</li>";
            }
            $output .= "</ul>";
        } else {
            $output = "Error: No items found";
        }
    
        echo $output;
    }
    
    // else if($_POST['action'] != "list") {
    //     echo "Error: Invalid action";
    // }
// }


// Call the function with the database object ($db) as an argument
// fetchCategoryList($db);


?>