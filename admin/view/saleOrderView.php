<?php
require_once '../connection.inc.php';
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
$db = new dbConnector();
if (isset($_POST['moduleid'])) {
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid' => $_SESSION['userid'], 'moduleid' => $_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);

$sql = "select id,party_name from tbl_parties";
$parties = $db->readData($sql, []);
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <h3 class="box-title"><?php echo $_SESSION['current_module_name'] ?></h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <button type="button" class="btn btn-sm btn-info add-button" style="align-items: center;" data-toggle="modal" data-target="#myModal" <?php echo $permissions['insert'] ?>>
                                    Create New
                                </button>
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






                    <div class="modal fade" id="myModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Sale Order</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">
                                        <input type="hidden" id="userHiddenId" name="userHiddenName" value="" />
                                        <div class="form-group">
                                            <label for="party_id">Party Name</label>
                                            <select class="form-control modalyearstatus" name="party_id" id="party_id">
                                                <option value="" selected>Select Party</option>
                                                <?php foreach ($parties as $party) {
                                                    echo "<option value='{$party['id']}'>{$party['party_name']}</option>";
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bill_no">Bill No</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Bill No" id="bill_no" name="bill_no" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="voucher_no">Voucher No</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Voucher Number" id="voucher_no" name="voucher_no">
                                        </div>
                                        <div class="form-group">
                                            <label for="order_date">Date of Order</label>
                                            <input class="form-control yearlimit modalyearfrom" type="date" min="1900-01-01" max="2030-12-31" placeholder="Enter Date of Order" id="order_date" name="order_date" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="delivery_date">Date of Delivery</label>
                                            <input class="form-control yearlimit modalyearfrom" type="date" min="1900-01-01" max="2030-12-31" placeholder="Enter Date of Delivery" id="delivery_date" name="delivery_date">
                                        </div>
                                        <div class="form-group">
                                            <label for="payment_mode">Payment Mode</label>
                                            <select class="form-control modalyearstatus" name="payment_mode" id="payment_mode">
                                                <option value="" selected>Select Payment mode</option>
                                                <option value="cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Online">Online</option>
                                                <option value="Card">Card</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="delivery_address">Delivery Address</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Delivery Address" id="delivery_address" name="delivery_address">
                                        </div>
                                        <div class="form-group">
                                            <label for="terms">Terms</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Terms" id="terms" name="terms">
                                        </div>
                                        <div class="form-group">
                                            <label for="other_detail">Other Details</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Other Details" id="other_detail" name="other_detail">
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="Status">Status</label>
                                            <select class="form-control modalyearstatus" name="status" id="status">
                                                <option value="" selected>Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div> -->
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
                                        <button type="button" class="btn btn-secondary modalClose" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                <!-- <div class="alert alert-dark" id="hmsg" style="display:none;"></div> -->
                                <div id="msg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="myModalUpdate">

                    </div>
                    <div class="card-body--">
                        <div class="table-responsive table-container">
                            <table class="table">
                                <thead class="thead">
                                    <tr>
                                        <th class="serial">#</th>
                                        <th>Party Name</th>
                                        <th>Bill No</th>
                                        <th>Voucher No</th>
                                        <th NOWRAP>Order Date</th>
                                        <th NOWRAP>Delivery Date</th>
                                        <th>Payment Mode</th>
                                        <th>Delivery Address</th>
                                        <th>Terms</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="saleOrderTableContents">

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
<script src="../assets/js/saleordermaster.js" type="text/javascript"></script>
</body>

</html>