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
        // $sql = "";
        // $bomdata = $db->readSingleRecord($sql);
        // if (isset($bomdata)) {
        // $rowCounts = count($bomdata);
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        $sql = "select s.id,d.dept_name, p.product_name, u.unit, s.rate, s.qty,s.status 
        from tbl_stock s 
        inner join tbl_products p 
        on s.prod_id=p.id
        inner join tbl_deparment d
        on s.dept_id= d.id
        inner join tbl_unit u
        on s.unit_id=u.id
        ";
        $stockdata = $db->readData($sql);
        // $result['bom_data'] = $bomdata;
        $output = "";
        if(isset($stockdata)){
        foreach ($stockdata as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["dept_name"]}</td>
                        <td>{$row["product_name"]}</td>
                        <td>{$row["rate"]}</td>
                        <td>{$row["unit"]}</td>
                        <td>{$row["qty"]}</td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_stock' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_stock' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
    // }
        // $result['material_data'] = $output;
    }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($output);
}
//End

if($_POST['action'] == "load_subcategories"){
    $sql = "Select id,subcategory_name from tbl_subcategory where category_id={$_POST['category_id']} and status=1";
    $subcategories = $db->readData($sql);
    $list = "<option value='' selected>Subcategory..</option>";
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
    $list = "<option value='' selected>Product..</option>";
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
        $deptid = $_POST['dept'];
        $productid = $_POST['product'];
        $mrate = $_POST['mrate'];
        $munitid = $_POST['munit'];
        $mqty = $_POST['mqty'];
        $cost = $_POST['cost'];
        $ustatus = $_POST['status'];
        $sql = "select id from tbl_stock where dept_id=:deptid and prod_id=:productid";
        $params = ['deptid' => $deptid, 'productid' => $productid];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_stock(compid,prod_id,dept_id,unit_id,rate,qty,status) values((select id from company_master),:prod_id,:dept_id,:unit_id,:rate,:qty,:status)";
            $params = [ 'dept_id' => $deptid,'prod_id' => $productid,'unit_id'=>$munitid, 'rate' => $mrate, 'qty'=>$mqty, 'status' => $_POST['status']];
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                log_user_action($_SESSION['userid'], 'create', "tbl_stock", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_stock where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "delete from tbl_stock where id =:id";
        $params = ['id' => $id];
        $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_stock", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
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
        $sql = "select * from tbl_stock where id  = {$id}";
        $row = $db->readSingleRecord($sql);
        if(isset($row)){
            $prod_id = $row['prod_id'];
            $sql = "select category_id, subcategory_id from tbl_products where id={$prod_id}";
            $prod_data=$db->readSingleRecord($sql);
            $row['category_id']= $prod_data['category_id'];
            $row['subcategory_id']= $prod_data['subcategory_id'];
            $cost=$row['rate']*$row['qty'];
            $row['cost']= number_format((float)$cost, 2, '.', '');
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
        // $targetFile = "";
        // $saveRecord = true;
        $id = $_POST['modalid'];
        //get old record for user log
        $sql = "select * from tbl_stock where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $deptid = $_POST['dept'];
        $productid = $_POST['product'];
        $oldRecord = $db->readSingleRecord($sql, $params);
        $sql = "select id from tbl_stock where dept_id=:deptid and prod_id=:productid and id!={$id}";
        $params = ['deptid' => $deptid, 'productid' => $productid];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "update tbl_stock set dept_id=:deptid, prod_id=:product, unit_id=:unit, rate=:rate, qty=:qty where id=:id";
            $params = ['id'=>$id,'deptid'=>$deptid,  'product' => $_POST['product'], 'unit' => $_POST['munit'], 'rate' => $_POST['mrate'], 'qty' => $_POST['mqty']];
            $recordId = $db->ManageData($sql, $params);
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_stock", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
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
