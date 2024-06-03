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
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Tax</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">

                                        <input type="hidden" id="modalid" name="modalid" value="" />
                                        <div class="form-group">
                                            <label for="taxname">Tax Name</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Tax Name" id="taxname" name="taxname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="taxpercentage">Tax Percentage</label>
                                            <input class="form-control yearlimit modalyearfrom" type="tel" placeholder="Enter Tax Percentage" id="taxpercentage" name="taxpercentage" pattern="[0-9]{2}" required>
                                            <small id="mobileHelp" class="form-text text-muted">Please enter 2 to 3 digits percentage.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="Status">Status</label>
                                            <select class="form-control modalyearstatus" name="status" id="status">
                                                <option value="" selected>Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                                        <th>Tax NAME</th>
                                        <th>Tax Percentage</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="taxTableContents">

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
<script src="../assets/js/taxmaster.js" type="text/javascript"></script>
</body>

</html>