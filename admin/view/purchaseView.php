<?php
require_once '../connection.inc.php';
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
$db = new dbConnector();
if(isset($_POST['moduleid'])){
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);

$sql = "select id,vendor_name FROM tbl_vendors where status=1";
$vendors = $db->readData($sql);

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
                                <button type="button" class="btn btn-sm btn-info add-button" style="align-items: center;" data-toggle="modal" data-target="#myModal" <?php echo $permissions['insert']?>>
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
                                    <h4 class="modal-title">Add Purchase Details</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">
                                       
                                        <input type="hidden" id="purchaseHiddenId" name="purchaseHiddenName" value="" />
                                        
                                        <div class="form-group">
                                            <label for="billnumber">Enter Bill Number</label>
                                            <input class="form-control" type="text" placeholder="Enter Bill Number" id="billNumberId" name="billNumberName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="vaedername">Select Vendor Name</label>
                                            <!-- <input class="form-control" type="text" placeholder="Enter Vendor Name" id="vendorId" name="vendorName" required> -->
                                            <select class="form-control" id="vendorId" name="vendorName" required>
                                                <option value="">Select Vendor Name</option>
                                                <?php 
                                                    foreach($vendors as $vendor){
                                                        echo "<option value='{$vendor['id']}'>{$vendor['vendor_name']}</option>";
                                                        
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="cost">Enter Cost</label>
                                            <input class="form-control" type="number" placeholder="Enter Cost" id="costId" name="costName" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="tax_amount">Enter Tax Amount</label>
                                            <input class="form-control" type="number" placeholder="Enter Tax Amount" id="taxId" name="taxName">
                                        </div>
                                        <div class="form-group">
                                            <label for="totalcost">Total Cost</label>
                                            <input class="form-control" type="number" placeholder="Total Cost Amount" id="totalCostId" name="totalCostName" readonly>
                                        </div>
                                        
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
                                        <button type="button" class="btn btn-secondary modalClose" id="btnClose" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                                <!-- <div class="alert alert-dark" id="hmsg" style="display:none;"></div> -->
                                <div id="msg"></div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="card-body--">    
                        <div class="table-responsive table-container">
                            <table class="table">
                                <thead class="thead">
                                    <tr>
                                        <th class="serial">#</th>
                                        <!-- <th>BOM Name</th> -->
                                        <th>BILL NUMBER</th>
                                        <th>VENDOR NAME</th>
                                        <th>COST</th>
                                        <th>TAX</th>
                                        <th>TOTAL AMOUNT</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="purchaseTableContents">

                                </tbody>
                            </table>
                            <div class="ui-widget">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('../template/footer.inc.php') ?>
<script src="../assets/js/purchasemaster.js" type="text/javascript"></script>

</body>

</html>