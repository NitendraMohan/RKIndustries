<?php
// require_once '../utility/sessions.php';
require_once '../connection.inc.php';
require("../template/top.inc.php");
$db = new dbConnector();
if (isset($_POST['bomid'])) {
    $_SESSION['bomid'] = $_POST['bomid'];
    echo 'bom id ' . $_SESSION['bomid'];
}
if(isset($_POST['moduleid'])){
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
$permissions = $db->get_buttons_permissions($params);
$sql =  "SELECT id,username FROM tbl_users";
$users_list = $db->readData($sql);

$sql = "Select id,expanse_name from tbl_other_charges where status=1";
$expanses = $db->readData($sql);
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <h3 class="box-title">Other Charges</h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                            <input type="hidden" id="bomid" name="bomid" value='<?= $_SESSION['bomid'] ?>'>
                                Product Name: <h3 class="box-title" id="bomname" name="bomname"></h3>
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
                                Brand Name: <h3 class="box-title" id="brandname" name="brandname"></h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Material Cost: <h3 class="box-title" id="materialcost" name="materialcost">0.00</h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Other Cost: <h3 class="box-title" id="othercost" name="othercost">0.00</h3>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                            <div class="card-body">
                                Total Cost: <h3 class="box-title" id="totalcost" name="totalcost">0.00</h3>
                            </div>
                        </div>
                    </div>

                   

                    <!-- <div class="container-fluid border border-primary bg-light p-4"> -->
                    <form action="" class="form-inline" method="post" id="userForm">
                        <!-- <div class="modal-body"> -->

                        <input type="hidden" id="modalid" name="modalid" value="" />
                        <div class="form-row  border border-primary bg-light p-2">
                            <h5 style="color:blue;">Add New Expanse</h5>
                            <div class="form-group col-md">
                                <select class="form-control modalyearstatus" name="expanse_name" id="expanse_name" style="width: 250px;">
                                    <option value="" selected>Expanse Name..</option>
                                    <?php foreach ($expanses as $expanse) {
                                        echo "<option value='{$expanse['id']}'>{$expanse['expanse_name']}</option>";
                                    } ?>
                                </select>
                                <!-- <td ><div class="form-check-inline" style="width: 150px;margin:10px;">
                                        <input class="form-check-input" type="checkbox" id="is_applicable" value="0" />
                                        <label class="form-check-label" aria-describedby="is_applicable" id="lbis_applicable"" for="is_applicable">Is Applicable</label>
                                    </div>
                                </td> -->
                                <td ><div class="form-check-inline" style="width: 200px;margin:10px;">
                                        <input class="form-check-input" type="checkbox" id="is_percentage" name="is_percentage" value="1" />
                                        <label class="form-check-label" aria-describedby="is_percentage" id="lbis_percentage" for="is_percentage">Is Percentage</label>
                                    </div>
                                </td>
                                <td ><div class="form-check-inline" style="width: 200px;margin:10px;">
                                        <input class="form-check-input" type="checkbox" id="apply_on_material" name="apply_on_material" value="0" disabled />
                                        <label class="form-check-label" aria-describedby="apply_on_material" id="lbapply_on_material" for="apply_on_material">Apply Materialwise</label>
                                    </div>
                                </td>
                                <td>
                                <input class="form-control" type="number" placeholder="value" id="charge_value" name="charge_value" required step="any" style="width: 150px;margin:10px;">
                                </td>
                           
                                <!-- <select class="form-control modalyearstatus" name="status" id="status"  style="width: 150px;margin:10px;">
                                    <option value="" selected>Status..</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select> -->
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
                                        <th>Expanse Name</th>
                                        <!-- <th>Is Applicable</th> -->
                                        <th>Is Percentage</th>
                                        <th>Allow to set on materials</th>
                                        <th>Expanse Value</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="usersTableContents">

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
<script src="../assets/js/bomotherchargesmaster.js" type="text/javascript"></script>
</body>

</html>