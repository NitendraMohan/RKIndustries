<?php
// require_once '../utility/sessions.php';
require_once '../connection.inc.php';
require("../template/top.inc.php");

$db = new dbConnector();
if(isset($_POST['moduleid'])){
    $_SESSION['moduleid'] = $_POST['moduleid'];
    $_SESSION['current_module_name'] = $_POST['modulename'];
}
$params = ['userid'=>$_SESSION['userid'],'moduleid'=>$_SESSION['moduleid']];
// $_SESSION['moduleid'] = $_POST['moduleid'];
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
                                    <h4 class="modal-title">Add Unit</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="unitForm">
                                    <div class="modal-body">

                                        <input type="hidden" id="modalid" name="id" value="" />
                                        <div class="form-group">
                                            <label for="unitname">Unit</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Unit" id="unitname" name="unitname" required>
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

                    <!-- model for edit -->
                    <div class="modal fade" id="myModalUpdate">

                    </div>
                    <div class="card-body--">
                        <!-- <div class="table-stats order-table ov-h"> -->
                        <!-- end -->
                        <!-- <div class="table-stats order-table ov-h"> -->
                        <div class="table-responsive table-container">

                            <table class="table">

                                <thead class="thead">
                                    <tr>
                                        <th class="serial">#</th>
                                        <th style="width:60%" ;>UNIT</th>
                                        <th style="width: 10%;">STATUS</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="unitTableContents">

                                </tbody>
                            </table>
                        </div>


                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('../template/footer.inc.php') ?>
<script src="../assets/js/unitmaster.js" type="text/javascript"></script>
</body>

</html>