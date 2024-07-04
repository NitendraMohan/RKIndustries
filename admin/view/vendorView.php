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
                                    <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Add Vendor</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">

                                        <input type="hidden" id="modalid" name="modalid" value="" />
                                        <div class="form-group">
                                            <label for="image">Select Vendor Image</label>    
                                            <input class="form-control" type="file" name="image" id="image">
                                        </div>
                                        <img src="" alt="logo image" id="logo_image" name="logo_image" onerror="this.onerror=null; this.src='../images/favicon.png'" height="20%" width="20%"/>    
                                        <div class="form-group">
                                            <label for="vendorname">Vendor Name</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Vendor Name" id="vendorname" name="vendorname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="compname">Company Name</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Company Name" id="compname" name="compname" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="gstno">GST Number</label>
                                            <input class="form-control yearlimit modalyearfrom" type="tel" placeholder="Enter GST Number" id="gstno" name="gstno">
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Mobile Number</label>
                                            <input class="form-control" type="tel" placeholder="Enter Mobile Number" id="mobile" name="mobile" pattern="[0-9]{10}" required>
                                            <small id="mobileHelp" class="form-text text-muted">Please enter a 10-digit mobile number.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input class="form-control yearlimit modalyearfrom" type="email" placeholder="Enter Email" id="email" name="email" required>
                                            <small id="emailHelp" class="form-text text-muted">Please enter a valid email address (e.g., example@example.com).</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Address" id="address" name="address" required>
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
                                        <th>VENDOR NAME</th>
                                        <th>COMPANY NAME</th>
                                        <th>GST NUMBER</th>
                                        <th>MOBILE</th>
                                        <th>EMAIL</th>
                                        <th NOWRAP>COMPANY ADDRESS</th>
                                        <th>IMAGE</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="vendorsTableContents">

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
<script src="../assets/js/vendorsmaster.js" type="text/javascript"></script>
</body>

</html>