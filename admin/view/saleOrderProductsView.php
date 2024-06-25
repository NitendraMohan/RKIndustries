<?php
require_once '../connection.inc.php';
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
$db = new dbConnector();
if (isset($_POST['saleorder_id'])) {
    $_SESSION['saleorder_id'] = $_POST['saleorder_id'];
    echo 'bom id ' . $_SESSION['saleorder_id'];
}
if (isset($_POST['moduleid'])) {
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid' => $_SESSION['userid'], 'moduleid' => $_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);

$sql = "Select id,brand_name from tbl_brand where status=1";
$brands = $db->readData($sql);

$sql = "Select id,category_name from tbl_category where status=1";
$categories = $db->readData($sql);

$sql = "Select id,tax_name,tax_percentage from tbl_taxes where status=1";
$taxes = $db->readData($sql);

$sql = "Select id,unit from tbl_unit where status=1";
$units = $db->readData($sql);
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <input type="hidden" id="saleorder_id" name="saleorder_id" value='<?= $_SESSION['saleorder_id'] ?>'>
                                <h3 class="box-title">Sale Order Products</h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                Bill Number: <h3 class="box-title" id="bill_no" name="bill_no"></h3>
                            </div>  
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <div class="search-bar " id="search-bar">
                                    <!-- <label for="search">Search :</label> -->
                                    <input type="text" placeholder="Search here" id="search" autocomplete="off">
                                    <!-- <button type="submit">Search</button> -->
                                    <img src="../images/icon/search.png" alt="Lance Icon" style="height: 5%; width:5%; margin-right: 10px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Party Name: <h3 class="box-title" id="party_name" name="party_name"></h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Order Date: <h3 class="box-title" id="order_date" name="order_date"></h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Delivery Date: <h3 class="box-title" id="delivery_date" name="delivery_date"></h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Total Cost: <h3 class="box-title" id="title_total_cost" name="title_total_cost"></h3>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="container-fluid border border-primary bg-light p-4"> -->
                    <form action="" class="form-inline" method="post" id="userForm">
                        <!-- <div class="modal-body"> -->

                        <input type="hidden" id="modalid" name="modalid" value="" />
                        <div class="form-row  border border-primary bg-light p-2">
                            <h5 style="color:blue;">Add Sale Order Product</h5>
                            <div class="form-group col-md">
                            <select class="form-control modalyearstatus" name="brand" id="brand" style="width: 150px;">
                                    <option value="" selected>Brand..</option>
                                    <?php foreach ($brands as $brand) {
                                        echo "<option value='{$brand['id']}'>{$brand['brand_name']}</option>";
                                    } ?>
                                </select>
                                <select class="form-control modalyearstatus" name="category" id="category" style="width: 150px;">
                                    <option value="" selected>Category..</option>
                                    <?php foreach ($categories as $category) {
                                        echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                                    } ?>
                                </select>
                                <select class="form-control modalyearstatus" name="subcategory" id="subcategory" style="width: 150px;">
                                    <option value="" selected>Subcategory..</option>
                                    <?php foreach ($subcategories as $subcategory) {
                                        echo "<option value='{$subcategory['id']}'>{$subcategory['subcategory_name']}</option>";
                                    } ?>
                                </select>
                                <select class="form-control modalyearstatus" name="product" id="product" style="width: 300px;">
                                    <option value="" selected>Product..</option>
                                    <?php foreach ($products as $product) {
                                        echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
                                    } ?>
                                </select>
                                <select class="form-control modalyearstatus" name="munit" id="munit" style="width: 100px;">
                                    <option value="" selected>Unit..</option>
                                    <?php foreach ($units as $unit) {
                                        echo "<option value='{$unit['id']}'>{$unit['unit']}</option>";
                                    } ?>
                                </select>
                                <!-- <input class="form-control decimalplaces" type="text" placeholder="Rate" id="mrate" name="mrate" required style="width: 100px;">
                                <input class="form-control decimalplaces" type="text" placeholder="Qty" id="mqty" name="mqty" required style="width: 100px;"> -->
                                <input class="form-control" type="number" placeholder="Rate" id="mrate" name="mrate" required step="any" style="width: 100px;">
                                <input class="form-control" type="number" placeholder="Qty" id="mqty" name="mqty" required step="any" style="width: 100px;">
                                <input class="form-control decimalplaces" type="text" readonly placeholder="0" id="cost" name="cost" required style="width: 100px;">
                                <select class="form-control modalyearstatus" name="tax_id" id="tax_id" style="width: 100px;">
                                    <option value="" selected>Tax%..</option>
                                    <?php foreach ($taxes as $tax) {
                                        echo "<option value='{$tax['id']}'>{$tax['tax_percentage']}</option>";
                                    } ?>
                                </select>
                                <input class="form-control" type="text" placeholder="0.00" id="tax_amt" name="tax_amt" step="any" style="width: 100px;">
                                <input class="form-control decimalplaces" type="text" readonly placeholder="0" id="total_cost" name="total_cost" required style="width: 100px;">
                                <select class="form-control modalyearstatus" name="status" id="status">
                                    <option value="" selected>Select</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save"><i class="fa fa-plus"></i></button>
                                <!-- <button type="button" class="btn btn-secondary modalClose" id="btnClose" data-dismiss="modal">Close</button> -->
                            </div>
                        </div>

                    </form>
                    <div id="msg"></div>
                    <!-- </div> -->




                    <div class="card-body--">
                        <div class="table-responsive table-container">
                            <table class="table">
                                <thead class="thead">
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Rate</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>Tax</th>
                                        <th>Total Cost</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>





                                <tbody class="tableContents" id="saleOrderProductsTableContents">

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="clearfix"></div>
<footer class="page-footer">
            <div class="page-inner bg-white">
               
            </div>
         </footer> -->
<?php require('../template/footer.inc.php') ?>

<script src="../assets/js/saleorderproductsmaster.js" type="text/javascript"></script>
</body>

</html>