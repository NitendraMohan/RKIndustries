<?php
// require_once '../utility/sessions.php';
require("../template/top.inc.php");
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card-body">
                                <h3 class="font-weight-bold">UNIT MASTER</h3>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="search-bar " id="search-bar">
                                <!-- <label for="search">Search :</label> -->
                                <input type="text" placeholder="Search here" id="search" autocomplete="off">
                                <!-- <button type="submit">Search</button> -->
                                <img src="../images/icon/search.png" alt="Lance Icon" style="height: 5%; width:5%;">
                            </div>

                        </div>
                    </div>


                    <button type="button" class="btn btn-primary" style="margin:20px;" data-toggle="modal" data-target="#myModal">
                        Create New
                    </button>
                    <div class="card-body--">
                        <div class="table-stats order-table ov-h">

                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
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








                            <div class="table-container">
                                <table class="table">
                                    <thead class="sticky-top">
                                        <tr>
                                            <th class="serial">#</th>
                                            <th>ID</th>
                                            <th>UNIT</th>
                                            <th>STATUS</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableContents" id="unitTableContents">

                                    </tbody>
                                </table>
                            </div>


                        </div>
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