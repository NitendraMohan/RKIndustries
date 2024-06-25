<?php
require_once '../connection.inc.php';
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
$db = new dbConnector();
if (isset($_POST['purchaseId'])) {

    $_SESSION['purchaseId'] = $_POST['purchaseId'];
    // echo 'bom id ' . $_SESSION['bomid'];
}
if (isset($_POST['moduleid'])) {
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid' => $_SESSION['userid'], 'moduleid' => $_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);

$sql = "Select id,category_name from tbl_category where status=1";
$categories = $db->readData($sql);

$sql = "Select id,unit from tbl_unit where status=1";
$units = $db->readData($sql);

$sql = "SELECT * FROM tbl_products WHERE status = 1";
$products = $db->readData($sql);
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <input type="hidden" id="purchaseHiddenId" name="purchaseHiddenName" value='<?= $_SESSION['purchaseId'] ?>'>
                                <h3 class="box-title">PURCHASE ITEMS</h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                Vandor Name: <h3 class="box-title" id="vendorId" name="vendorName"></h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <div class="search-bar " id="search-bar">
                                    <input type="text" placeholder="Search here" id="search" autocomplete="off">
                                    <img src="../images/icon/search.png" alt="Lance Icon" style="height: 5%; width:5%; margin-right: 10px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <!-- <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Brand Name: <h3 class="box-title" id="brandname" name="brandname"></h3>
                            </div>
                    </div> -->
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                BIll No: <h3 class="box-title" id="billNoId" name="billNoName">0.00</h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Items Cost: <h3 class="box-title" id="itemCostId" name="itemCostName">0.00</h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Tax Amount: <h3 class="box-title" id="taxAmountId" name="taxAmountName">0.00</h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Total Cost: <h3 class="box-title" id="totalcostId" name="totalcostName">0.00</h3>
                            </div>
                        </div>
                        <!-- <div class="col-xs-6 col-sm-2 col-md-2 col-lg-4">
                            <div class="card-body">
                                <img src="" alt="product image" id="product_image" name="product_image" onerror="this.onerror=null; this.src='../images/favicon.png'" height="40%" width="40%" />
                            </div>
                        </div> -->
                    </div>

                    <!-- <div class="container-fluid border border-primary bg-light p-4"> -->
                    <form action="" class="form-inline" method="post" id="userForm">
                        <!-- <div class="modal-body"> -->

                        <input type="hidden" id="modalid" name="modalid" value="" />
                        <div class="form-row  border border-primary bg-light p-2">
                            <h5 style="color:blue;">Add New Items</h5>
                            <div class="form-group col-md">
                                <select class="form-control" name="productName" id="productId" style="width: 300px;">
                                    <option value="" selected>Product..</option>
                                    <?php foreach ($products as $product) {
                                        echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
                                    } ?>
                                </select>
                                <input class="form-control" type="number" placeholder="Rate" id="mrateId" name="mrateName" required step="any" style="width: 130px;">
                                <select class="form-control" name="munitName" id="munitId" style="width: 100px;">
                                    <option value="" selected>Unit..</option>
                                    <?php foreach ($units as $unit) {
                                        echo "<option value='{$unit['id']}'>{$unit['unit']}</option>";
                                    } ?>
                                </select>
                                <input class="form-control" type="number" placeholder="Qty" id="mqtyId" name="mqtyName" required step="any" style="width: 100px;">
                                
                                <input class="form-control" type="number" readonly placeholder="Cost" id="costId" name="costName" step="any" style="width: 150px;">
                                <input class="form-control" type="number" placeholder="Tax(%)" id="taxPerId" name="taxPerName" step="any" style="width: 100px;">
                                <input class="form-control" type="number"  id="taxAmtId" name="taxAmtName" step="any" readonly placeholder="Tax Amt" style="width: 120px;">
                                <input class="form-control" type="number" readonly placeholder="Total Cost" id="totalCostId" name="totalCostName" step="any" style="width: 150px;">
                              
                                <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save"><i class="fa fa-plus"></i></button>
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
                                        <th>PRODUCT</th>
                                        <th>RATE</th>
                                        <th>UNIT</th>
                                        <th>QTY</th>
                                        <th>COST</th>
                                        <th>TAX(%)</th>
                                        <th>TAX AMOUNT</th>
                                        <th>TOTAL COST</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>





                                <tbody class="tableContents" id="bommaterialsTableContents">

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

<script src="../assets/js/purchaseItemsMaster.js" type="text/javascript"></script>
</body>

</html>