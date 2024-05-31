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
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <h3 class="box-title">USER MASTER </h3>
                                <!-- <h3 class="font-weight-bold">UNIT MASTER</h3> -->
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                            <div class="card-body">
                                <button type="button" class="btn btn-sm btn-primary add-button" style="align-items: center;" data-toggle="modal" data-target="#myModal">
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
                                    <h4 class="modal-title">Add User</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">

                                        <input type="hidden" id="modalid" name="modalid" value="" />
                                        <div class="form-group">
                                            <label for="image">Select User Image</label>    
                                            <input class="form-control" type="file" name="image" id="image">
                                        </div>
                                        <img src="" alt="logo image" id="logo_image" name="logo_image" onerror="this.onerror=null; this.src='../images/info.png'" height="20%" width="20%"/>    
                                        <div class="form-group">
                                            <label for="username">User Name</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter User Name" id="username" name="username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control modalyearstatus" name="role" id="role">
                                                <option value="" selected>Select</option>
                                                <option value="admin">Admin</option>
                                                <option value="user">User</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender">Gender</label>
                                            <select class="form-control modalyearstatus" name="gender" id="gender">
                                                <option value="" selected>Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="dob">Date of Birth</label>
                                            <input class="form-control yearlimit modalyearfrom" type="date" min="1900-01-01" max="2030-12-31" placeholder="Enter Date of Birth" id="dob" name="dob" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="mobile">Mobile Number</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Mobile Number" id="mobile" name="mobile" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Email" id="email" name="email" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Address</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Address" id="address" name="address" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Initial Passwod</label>
                                            <input class="form-control yearlimit modalyearfrom" type="password" placeholder="Enter Initial Password" id="password" name="password" required>
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
                                        <th>USER NAME</th>
                                        <th>ROLE</th>
                                        <th>GENDER</th>
                                        <th NOWRAP>DATE OF BIRTH</th>
                                        <th>MOBILE</th>
                                        <th>EMAIL</th>
                                        <th NOWRAP>HOME ADDRESS</th>
                                        <th>IMAGE</th>
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
<script src="../assets/js/usersmaster.js" type="text/javascript"></script>
</body>

</html>