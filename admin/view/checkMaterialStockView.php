<?php
require_once '../connection.inc.php';
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
$db = new dbConnector();
if (isset($_POST['bomid'])) {
    $_SESSION['bomid'] = $_POST['bomid'];
    $_SESSION['product_id'] = $_POST['product_id'];
    $_SESSION['saleorder_id'] = $_POST['saleorder_id'];
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
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <input type="hidden" id="bomid" name="bomid" value='<?= $_SESSION['bomid'] ?>'>
                                <input type="hidden" id="product_id" name="product_id" value='<?= $_SESSION['product_id'] ?>'>
                                <input type="hidden" id="saleorder_id" name="saleorder_id" value='<?= $_SESSION['saleorder_id'] ?>'>
                                <h3 class="box-title module-head"><<&nbsp;&nbsp; Check Material Stock &nbsp;&nbsp;>></h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                Product Name and Qty: <h3 class="box-title" id="productname" name="productname"></h3>
                            </div>
                        </div>
                        <!-- <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Ordered Qty: <h3 class="box-title" id="orderedqty" name="orderedqty"></h3>
                            </div>
                        </div> -->
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

                    <!-- <div class="container-fluid border border-primary bg-light p-4"> -->
                    <form action="" class="form-inline" method="post" id="userForm">
                        <!-- <div class="modal-body"> -->
                        <input type="hidden" id="modalid" name="modalid" value="" />
                        
                    </form>
                    <div id="msg"></div>
                    <!-- </div> -->




                    <div class="card-body--">
                        <div class="table-responsive table-container">
                            <table class="table">
                                <thead class="thead">
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Product Name</th>
                                        <th>Rate</th>
                                        <th >Unit</th>
                                        <th>Qty per product</th>
                                        <th>Qty Required</th>
                                        <th>Qty in stock</th>
                                        <th>Qty more required</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="checkStockTableContents">

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

<script src="../assets/js/checkmaterialstock.js" type="text/javascript"></script>
</body>

</html>