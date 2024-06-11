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
        $sql = "select b.id, b.bom_name, p.product_name, br.brand_name, u.unit, b.qty,b.detail,b.image,b.status 
        from tbl_bom_product b 
        inner join tbl_products p 
        on b.product_id=p.id
        inner join tbl_unit u
        on b.unit_id=u.id
        inner join tbl_brand br
        on b.brand_id=br.id where b.id={$_POST['bomid']}";
        $bomdata = $db->readSingleRecord($sql);
        if (isset($bomdata)) {
        $rowCounts = count($bomdata);
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        $sql = "select bm.id, p.product_name, u.unit, bm.rate, bm.qty,bm.cost,bm.status 
        from tbl_bom_material bm 
        inner join tbl_products p 
        on bm.product_id=p.id
        inner join tbl_unit u
        on bm.unit_id=u.id
        where bm.bom_id={$_POST['bomid']}";
        $materialdata = $db->readData($sql);
        $result['bom_data'] = $bomdata;
        $output = "";
        if(isset($materialdata)){
        foreach ($materialdata as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["product_name"]}</td>
                        <td>{$row["rate"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>{$row["qty"]}</td>
                        <td>{$row["cost"]}</td>
                        <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='bom_material' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='bom_material' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
    }
        $result['material_data'] = $output;
    }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($result);
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


if($_POST['action'] == "load_rateunit"){
    $sql = "Select id,unit_id,price from tbl_products where id={$_POST['product_id']} and status=1";
    $units = $db->readSingleRecord($sql);
    // $list = "<option value='' selected>Unit..</option>";
    echo json_encode($units);
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

//Insert data into database
if ($_POST['action'] == "insert") {
    try {
        $targetFile = null;
        $saveRecord = true;
        $bomid = $_SESSION['bomid'];
        $categoryid = $_POST['category'];
        $subcategoryid = $_POST['subcategory'];
        $productid = $_POST['product'];
        $mrate = $_POST['mrate'];
        $munitid = $_POST['munit'];
        $mqty = $_POST['mqty'];
        $cost = $_POST['cost'];
        $ustatus = $_POST['status'];
        $sql = "select id from tbl_bom_material where bom_id=:bomid and product_id=:productid";
        $params = ['bomid' => $bomid, 'productid' => $productid];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_bom_material(compid,bom_id,category_id,subcategory_id,product_id,unit_id,rate,qty,cost,status) values((select id from company_master),:bom_id,:category_id,:subcategory_id,:product_id,:unit_id,:rate,:qty,:cost,:status)";
            $params = [ 'bom_id' => $bomid,'category_id' => $categoryid,'subcategory_id' => $subcategoryid,'product_id' => $productid,'unit_id'=>$munitid, 'rate' => $mrate, 'qty'=>$mqty, 'cost' => $cost, 'status' => $_POST['status']];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_bom_material", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_bom_material where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_bom_material where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_bom_material", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_bom_material where id  = {$id}";
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
        // if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
        //     $targetDir = "../images/";
        //     $targetFile = $targetDir . $_FILES["image"]["name"];
        //     $uploadOk = 1;
        //     $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        //     // Validation here
        //     if ($_FILES["image"]["name"] !== "") {
        //         if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        //             $saveRecord = true;
        //         } else {
        //             $saveRecord = false;
        //             echo json_encode(array('success' => false, 'msg' => 'Error File Path! Record not saved'));
        //             exit;
        //         }
        //     }
        // }
        // else if(isset($_POST['image']) && $_POST['image']!=''){
        //     // echo $_POST['image'];
        //     $targetFile = $_POST['image'];
        // }
        $id = $_POST['modalid'];
        //get old record for user log
        $sql = "select * from tbl_bom_material where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        
        $sql = "update tbl_bom_material set category_id=:category, subcategory_id=:subcategory, product_id=:product, unit_id=:unit, rate=:rate, qty=:qty, cost=:cost where id=:id";
        $params = ['id'=>$id, 'category' => $_POST['category'], 'subcategory' => $_POST['subcategory'], 'product' => $_POST['product'], 'unit' => $_POST['munit'], 'rate' => $_POST['mrate'], 'qty' => $_POST['mqty'], 'cost' =>$_POST['cost']];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_bom_material", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
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
        from tbl_bom_product b 
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
            <td>" . ($row['status'] == 1 ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='bom_material' style='width:70px;'>Active</button>" : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='bom_material' style='width:70px;'>Deactive</button>") . "</td>
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
