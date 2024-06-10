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
        $sql = "select b.id, b.bom_name, p.product_name, br.brand_name, u.unit, b.qty,sum(bm.cost) as mcost,b.detail,b.image,b.status 
        from tbl_BOM_product b 
        inner join tbl_products p 
        on b.product_id=p.id
        inner join tbl_unit u
        on b.unit_id=u.id
        inner join tbl_brand br
        on b.brand_id=br.id
        inner join tbl_bom_material bm
        on b.id=bm.bom_id";
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
                        <td>{$row["product_name"]}</td>
                        <td>{$row["brand_name"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>{$row["qty"]}</td>
                        <td>{$row["mcost"]}</td>
                        <td>0</td>
                        <td>0</td>
                        <td><img src='{$row["image"]}' class='img-circle' height='40px' width='auto' /></td>
                        <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            <button class='btn btn-info btn-sm material' title='Materials' data-id={$row["id"]}><i class='fa fa-chevron-right aria-hidden='true'>‌</i></button>
                            <button class='btn btn-primary btn-sm material' title='Other Charges' data-id={$row["id"]}><i class='fa fa-inr' aria-hidden='true'></i></button>
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

if($_POST['action'] == "load_products"){
    $sql = "Select id,product_name from tbl_products where subcategory_id={$_POST['subcategory_id']} and status=1";
    $products = $db->readData($sql);
    $list = "<option value='' selected>Select..</option>";
    if(isset($products)){
        foreach($products as $product){
            $list.="<option value='{$product['id']}'>{$product['product_name']}</option>";
        }
    }
    echo $list;
}

if($_POST['action'] == "load_brands"){
    $sql = "Select b.id,b.brand_name from tbl_brand b inner join tbl_brandproduct bp on b.id=bp.brandid where bp.productid={$_POST['product_id']} and bp.status=1";
    $products = $db->readData($sql);
    $list = "<option value='' selected>Select..</option>";
    if(isset($products)){
        foreach($products as $product){
            $list.="<option value='{$product['id']}'>{$product['brand_name']}</option>";
        }
    }
    echo $list;
}
if($_POST['action'] == "show_material"){
    header('Location: /employeeinfo-organization?'.$_SERVER['QUERY_STRING']);
      die();
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
        $bomname = strtoupper($_POST['bomname']);
        $categoryid = $_POST['category'];
        $subcategoryid = $_POST['subcategory'];
        $productid = $_POST['product'];
        $brandid = $_POST['brand'];
        $unitid = $_POST['unit'];
        $qty = $_POST['qty'];
        $detail = $_POST['detail'];
        $ustatus = $_POST['status'];
        $sql = "select id from tbl_BOM_product where bom_name=:bomname";
        $params = ['bomname' => $bomname];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_BOM_product(compid,bom_name,category_id,subcategory_id,product_id,brand_id,unit_id,qty,image,detail,status) values((select id from company_master),:bomname,:category,:subcategory,:product,:brand,:unit,:qty,:image,:detail,:status)";
            $params = [ 'bomname' => $bomname,'category' => $categoryid,'subcategory' => $subcategoryid,'product' => $productid,'brand' => $brandid, 'unit'=>$unitid, 'qty'=>$qty, 'status' => $_POST['status'], 'detail' => $detail, 'image' => $targetFile ?? '../images/favicon.png'];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_BOM_product", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_BOM_product where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_BOM_product where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_BOM_product", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_BOM_product where id  = {$id}";
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
        $sql = "select * from tbl_BOM_product where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        
        $sql = "update tbl_BOM_product set bom_name=:bomname, category_id=:category,subcategory_id=:subcategory, product_id=:product, brand_id=:brand,unit_id=:unit,qty=:qty,image=:image,status=:status where id=:id";
        $params = ['id'=>$id, 'bomname' => $_POST['bomname'], 'category' => $_POST['category'],'subcategory' => $_POST['subcategory'],'product' => $_POST['product'], 'brand' => $_POST['brand'], 'unit' => $_POST['unit'], 'qty' => $_POST['qty'], 'status' => $_POST['status'], 'image' => $targetFile ?? '../images/favicon.png'];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_BOM_product", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select b.id, b.bom_name, p.product_name, br.brand_name, u.unit, b.qty,b.detail,b.image,b.status 
        from tbl_BOM_product b 
        inner join tbl_products p 
        on b.product_id=p.id
        inner join tbl_unit u
        on b.unit_id=u.id
        inner join tbl_brand br
        on b.brand_id=br.id where p.product_name like '%{$search_value}%' or b.bom_name like '%{$search_value}%' or br.brand_name like '%{$search_value}%' or u.unit like '%{$search_value}%' or b.qty like '%{$search_value}%' order by p.product_name";
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
            <td>{$row["bom_name"]}</td>
            <td>{$row["product_name"]}</td>
            <td>{$row["brand_name"]}</td>
            <td>{$row["unit"]}</td>
            <td>{$row["qty"]}</td>
            <td>{$row["detail"]}</td>
            <td><img src='{$row["image"]}' class='img-circle' height='40px' width='auto' /></td>
            <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
            <td>
                <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                <button class='btn btn-info btn-sm material' title='show materials' data-id={$row["id"]}><i class='fa fa-level-down' aria-hidden='true'></i></button>
                </td>
            </tr>";
            $sr++;
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo $output;
}
