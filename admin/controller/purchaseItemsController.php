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
        // print_r($_POST['purchaseId']);
        // die();
        $sql = "SELECT 
        p.id,p.billno,
        v.vendor_name as vname, 
        sum(coalesce(pi.cost,0)) as cost,
        sum(COALESCE(pi.tax_perc,0)) as taxper,
        sum(COALESCE(pi.tax_amt,0)) as taxamt,
        sum(COALESCE(pi.total_cost,0)) as totalcost
        FROM tbl_purchase p
        JOIN tbl_vendors v ON p.vendorid = v.id
        JOIN tbl_purchase_item pi on p.id = pi.purchase_id
        where p.id = {$_POST['purchaseId']} and pi.status = 1";
        
        
        $purchaseData = $db->readSingleRecord($sql);
        // print_r($purchaseData);
        // die();
        if (isset($purchaseData)) {
        $rowCounts = count($purchaseData);
        $params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
        $permissions = $db->get_buttons_permissions($params);
        $sr = 1;
        $sql = "SELECT pi.*,pr.product_name 
        FROM tbl_purchase_item pi
        JOIN tbl_purchase p ON pi.purchase_id = p.id
        JOIN tbl_products pr ON pi.prod_id = pr.id
        where pi.purchase_id = {$_POST['purchaseId']} ;";
        $purchaseItems = $db->readData($sql);
        
        $result['purchaseData'] =  $purchaseData;
        $output = "";
        if(isset($purchaseItems)){
        foreach ($purchaseItems as $row) {
            $output .= "<tr>
                        <td>{$sr}</td>
                        <td>{$row["product_name"]}</td>
                        <td>{$row["price"]}</td>
                        <td>{$row["unit_id"]}</td>
                        <td>{$row["qty"]}</td>
                        <td>{$row["cost"]}</td>
                        <td>{$row["tax_perc"]}</td>
                        <td>{$row["tax_amt"]}</td>
                        <td>{$row["total_cost"]}</td>
                        <td>" . ($row['status'] == 1 
                        ? "<button class='btn btn-success btn-sm btn_toggle' data-id={$row['id']} data-status='active' data-dbtable='tbl_purchase_item' style='width:70px;'>Active</button>" 
                        : "<button class='btn btn-secondary btn-sm btn_toggle' data-id={$row['id']} data-status='deactive' data-dbtable='tbl_purchase_item' style='width:70px;'>Deactive</button>") . "</td>
                        <td>
                            <button class='btn btn-success btn-sm unitEdit' data-toggle='modal' data-target='#myModal' data-id={$row["id"]} {$permissions['update']}><i class='fa fa-pencil' aria-hidden='true'></i></button>
                            <button class='btn btn-warning btn-sm unitDelete' data-id={$row["id"]} {$permissions['delete']}><i class='fa fa-trash' aria-hidden='true'></i></button>
                            </td>

                        </tr>";
            $sr++;
        }
    }
        $result['purchaseItem'] = $output;
    }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    echo json_encode($result);
}
//End

// if($_POST['action'] == "load_subcategories"){
//     $sql = "Select id,subcategory_name from tbl_subcategory where category_id={$_POST['category_id']} and status=1";
//     $subcategories = $db->readData($sql);
//     $list = "<option value='' selected>Select..</option>";
//     if(isset($subcategories)){
//         foreach($subcategories as $subcategory){
//             $list.="<option value='{$subcategory['id']}'>{$subcategory['subcategory_name']}</option>";
//         }
//     }
//     echo $list;
// }

if($_POST['action'] == "load_products"){
    $sql = "Select id,product_name from tbl_products where status=1";
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
        $purchaseId =  $_SESSION['purchaseId'];
        // $pId = $_POST['pid'];
        $productId = $_POST['productName'];
        $mrate = $_POST['mrateName'];
        $munitid = $_POST['munitName'];
        $pqty = $_POST['mqtyName'];
        $cost = $_POST['costName'];
        $taxPer = $_POST['taxPerName'];
        $taxAmt = $_POST['taxAmtName'];
        $totalCost = $_POST['totalCostName'];
        $sql = "select id from tbl_purchase_item where purchase_id=:purchaseid and prod_id=:productid";
        $params = ['purchaseid' =>  $purchaseId, 'productid' => $productId];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            $sql = "insert into tbl_purchase_item(compid,purchase_id,prod_id,price,unit_id,qty,cost,tax_perc,tax_amt,total_cost,status) values((select id from company_master),:purchase_id,:prod_id,:price,:unit_id,:qty,:cost,:tax_perc,:tax_amt,:total_cost,1)";
            $params = [ 'purchase_id' => $purchaseId,'prod_id' => $productId,'price' => $mrate,'unit_id' => $munitid,'qty'=>$pqty, 'cost' => $cost, 'tax_perc'=>$taxPer, 'tax_amt' => $taxAmt, 'total_cost' => $totalCost];
            
            $newRecordId = $db->insertData($sql, $params);
            if ($newRecordId) {
                
            //update query for tbl_puchase on the basis to tbl_purchase_item    
                $sql = "UPDATE tbl_purchase SET 
                cost = cost + :costAmount, 
                tax_amount = tax_amount + :taxAmount, 
                total_cost = total_cost + :totalCost 
                WHERE id = :purchaseId";
                $params = ['costAmount' => $cost, 'taxAmount' => $taxAmt, 'totalCost' =>  $totalCost, 'purchaseId' => $purchaseId];
                $db->ManageData($sql,$params);
                //end
                // echo $productId;
                // die();
                $sql = "SELECT id FROM tbl_stock WHERE prod_id = :proId";
                $params = ['proId' => $productId];
                $stockId = $db->getID($sql,$params);

                    // if($sid>0){
                //product qty updated in stock
                    $sql = "UPDATE tbl_stock set qty = qty + :pqty WHERE id = :sid";
                    $params = ['pqty' => $pqty, 'sid' => $stockId];
                    $db->ManageData($sql,$params);
                //end
                    // }else{
                    //     $sql = "INSERT INTO tbl_stock(compid,prod_id,dept_id,unit_id,rate,qty,status) VALUES((select id from company_master), :prod_id, :dept_id, :unit_id, :rate, :qty, )";
                    //     $params = ['prod_id' => $productId,'dept_id' => 14,'unit_id' =>  $munitid,'rate' => $mrate ,'qty' => $pqty ,1];
                    //     $db->insertData($sql,$params);
                    // }
                log_user_action($_SESSION['userid'], 'create', "tbl_purchase_item", $newRecordId, $_SESSION["username"]);
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
        $sql = "select * from tbl_purchase_item where id=:id";
        $params = ["id" => $_POST["id"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        //end

        //these field required for update purchase record
        $purchaseId = $oldRecord['purchase_id'];
        $cost       = $oldRecord['cost'];
        $taxAmt     = $oldRecord['tax_amt'];
        $totalCost  = $oldRecord['total_cost'];
        //end
        
        $oldQty = $oldRecord['qty'];
        $productId =$oldRecord['prod_id'];
        $sql = "SELECT qty FROM tbl_stock WHERE prod_id = {$productId};";
        $stockRecord = $db->readSingleRecord($sql);
        $stockQty = $stockRecord['qty'];

        if($stockQty >= $oldQty){
            //stock update
            $sql = "UPDATE tbl_stock set qty = qty - :pqty WHERE prod_id = :podId";
            $params = ['pqty' => $oldQty, 'podId' => $productId];
            $db->ManageData($sql,$params);
            //end

            //purchase table update
            $sql = "UPDATE tbl_purchase SET 
            cost = cost - :costAmount, 
            tax_amount = tax_amount - :taxAmount, 
            total_cost = total_cost - :totalCost 
            WHERE id = :purchaseId";
            $params = ['costAmount' => $cost, 'taxAmount' => $taxAmt, 'totalCost' =>  $totalCost, 'purchaseId' => $purchaseId];
            $db->ManageData($sql,$params);
            //end

            //delete from purchase item
            $sql = "delete from tbl_purchase_item where id =:id";
            $params = ['id' => $id];
            $recordId = $db->ManageData($sql, $params);
        if ($recordId) {
            log_user_action($_SESSION['userid'], $_POST['action'], "tbl_purchase_item", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
            echo 1;
        } 
        
        else {
            echo 0;
        }
        }else{
            echo 0; 
        }


        // $sql = "delete from tbl_purchase_item where id =:id";
        // $params = ['id' => $id];
        // $recordId = $db->ManageData($sql, $params);
        // if ($recordId) {
        //     log_user_action($_SESSION['userid'], $_POST['action'], "tbl_purchase_item", $_POST['id'], $_SESSION["username"], json_encode($oldRecord));
        //     echo 1;
        // } 
        
        // else {
        //     echo 0;
        // }
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
        $sql = "select * from tbl_purchase_item where id  = {$id}";
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
        $purchaseItemId = $_POST['modalid'];
        // print_r($_POST);
        // die();
        //get old record for user log
        $sql = "select * from tbl_purchase_item where id=:id";
        $params = ["id" => $_POST["modalid"]];
        $oldRecord = $db->readSingleRecord($sql, $params);
        //end
       
       
        $purchaseId = $oldRecord['purchase_id'];
        $oldQty = $oldRecord['qty'];

        $sql = "select id from tbl_purchase_item where purchase_id=:purchaseId and prod_id=:productId and id!=:id";
        $params = ['purchaseId' =>  $purchaseId, 'productId' => $_POST['productName'], 'id' =>$purchaseItemId];
        $result = $db->readSingleRecord($sql, $params);
        if (isset($result)) {
            echo json_encode(array('duplicate' => true));
        } else {
            
            $sql = "SELECT id,qty FROM tbl_stock WHERE prod_id =:productId";
            $params = ['productId' => $_POST['productName']];
            $res = $db->readSingleRecord($sql,$params);
           if ($res['qty'] > $oldQty)
                       {
            $sql = "update tbl_purchase_item set prod_id=:prodId,price=:price,unit_id=:unitId,qty=:qty,cost=:cost,tax_perc=:taxPer,tax_amt=:taxAmt,total_cost=:totalCost  where id=:id";
            $params = ['prodId' => $_POST['productName'], 'price' => $_POST['mrateName'], 'unitId' => $_POST['munitName'], 'qty' => $_POST['mqtyName'], 'cost' => $_POST['costName'], 'taxPer' => $_POST['taxPerName'], 'taxAmt' =>$_POST['taxAmtName'], 'totalCost' => $_POST['totalCostName'],'id'=>$purchaseItemId];
            $recordId = $db->ManageData($sql, $params);
            if ($recordId) {
                log_user_action($_SESSION['userid'], $_POST['action'], "tbl_purchase_item", $_POST['modalid'], $_SESSION["username"], json_encode($oldRecord));
                echo json_encode(array("success" => true, "msg" => "Success: record updated successfully."));
            } else {
                echo json_encode(array("success" => false, "msg" => "Record not updated"));
            }               
        } else {
            echo json_encode(array("success" => false, "msg" => "This product's record cannot be updated at this time."));
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
if($_POST['action']=="update_totalcost"){
    $bomid = $_POST['bomid'];
    $total_cost = $_POST['total_cost'];
    $sql = "update tbl_bom_product set bom_cost={$total_cost} where id={$bomid}";
    $recordId = $db->ManageData($sql);
    echo $recordId ?  1 :  0;
    
}
