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
        $sql = "SELECT p.id, (select category_name from tbl_category where id=p.category_id) as category_name ,(select subcategory_name from tbl_subcategory where id=p.subcategory_id) as subcategory_name,p.product_name, (select unit from tbl_unit where id=p.unit_id) as unit,p.price,p.image, p.status FROM tbl_products p order by p.product_name";
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
                        <td>{$row["category_name"]}</td>
                        <td>{$row["subcategory_name"]}</td>
                        <td>{$row["product_name"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>{$row["price"]}</td>
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

if($_POST['action'] == "load_subcategories"){
    $sql = "Select id,subcategory_name from tbl_subcategory where category_id={$_POST['category_id']} and status=1";
    $subcategories = $db->readData($sql);
    $list = "<option value='' selected>Select..</option>";
    if(isset($subcategories)){
        foreach($subcategories as $subcategory){
            $list.="<option value='{$subcategory['id']}'>{$subcategory['subcategory_name']}</option>";
        }
    }
    echo $list;
}

//Insert data into database
if ($_POST['action'] == "insert") {
    try {
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
        $productname = strtoupper($_POST['productname']);
        $categoryid = $_POST['category'];
        $subcategoryid = $_POST['subcategory'];
        $unitid = $_POST['unit'];
        $price = $_POST['price'];
        $ustatus = $_POST['status'];
        $sql = "select id from tbl_products where product_name=:productname";
        $params = ['productname' => $productname];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_products(compid,category_id,subcategory_id,product_name,unit_id,price,image,status) values((select id from company_master),:category,:subcategory,:productname,:unit,:price,:image,:status)";
            $params = [ 'productname' => $productname,'category' => $categoryid,'subcategory' => $subcategoryid,'unit'=>$unitid,'price'=>$price, 'status' => $_POST['status'], 'image' => $targetFile ?? '../images/favicon.png'];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_products", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_products where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_products where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_products", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_products where id  = {$id}";
        $row = $db->readSingleRecord($sql);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($row);
}
//End
//Model display with data for updation record
if ($_POST['action'] == "get_subcategories") {
    try {
        $output1 = '';
        $id = $_POST['sub_id'];
        $sql = "select id,subcategory_name from tbl_subcategory where id  = {$id}";
        $rows = $db->readData($sql);
        foreach($rows as $row){
            echo "<>";
        }
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
        $sql = "select * from tbl_products where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        
        $sql = "update tbl_products set category_id=:category,subcategory_id=:subcategory, product_name=:productname,unit_id=:unit,price=:price,image=:image,status=:status where id=:id";
        $params = ['id'=>$id, 'category' => $_POST['category'],'subcategory' => $_POST['subcategory'],'productname' => $_POST['productname'], 'unit' => $_POST['unit'], 'price' => $_POST['price'], 'status' => $_POST['status'], 'image' => $targetFile ?? '../images/favicon.png'];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_products", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
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
        // $conn = new PDO($this->dsn, $this->productname, $this->password);
        $sql = "SELECT (select category_name from tbl_category where id=p.category_id),(select subcategory_name from tbl_products where id=p.subcategory_id),p.product_name, (select unit from tbl_unit where id=p.unit_id),p.price,p.image, p.status FROM tbl_products p where p.product_name like '%{$search_value}%' or p.category_name like '%{$search_value}%' or p.subcategory_name like '%{$search_value}%' or p.unit like '%{$search_value}%' or p.price like '%{$search_value}%' order by p.product_name";
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
            <td>{$row["category_name"]}</td>
            <td>{$row["subcategory_name"]}</td>
            <td>{$row["product_name"]}</td>
            <td>{$row["unit"]}</td>
            <td>{$row["price"]}</td>
            <td><img src='{$row["image"]}' class='img-circle' height='40px' width='auto' /></td>
            <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
            <td>
                <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                </td>
            </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
