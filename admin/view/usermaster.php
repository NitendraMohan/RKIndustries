<?php
require("../template/top.inc.php");

?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">USER REGISTRATION</h3>
                    </div>
                    <button type="button" class="btn btn-primary" style="margin:20px;" data-toggle="modal" data-target="#myModal" onclick="setModelValues('')">
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
                                            <h4 class="modal-title">User Registration</h4>
                                        </div>
                                        <!-- Modal body -->
                                        <form action="" method="post">
                                            <div class="modal-body">
                                                <input type="hidden" id="modalid" name="id" value="" />
                                                <div class="form-group">
                                                    <label for="userid">Employee Id</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter name" id="uname" name="uname" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="User Role">Role</label>
                                                    <select class="form-control" name="userrole" id="userrole">
                                                        <option value="">Select Role</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="user">Employee</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deptid">Department Name</label>
                                                    <select class="form-control" name="deptname" id="depetname">
                                                        <option value="">Select Department</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="user">Employee</option>
                                                    </select>
                                                </div>


                                                <div class="form-group">
                                                    <label for="userfirstname">First Name</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter First name" id="ufname" name="ufname" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="UserName">Last Name</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Last name" id="ulname" name="ulname" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="mobileno">Mobile No</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Mobile No" id="mobileno" name="mobileno" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="uemail" id="uemail">
                                                </div>
                                                <div class="iamge">
                                                    <label for="email">Image</label>
                                                    <input type="file" class="form-control" name="uemail" id="uemail">
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
                                        </form>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" id="btnSave" data-id="save">Submit</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                        <div class="alert alert-dark" id="hmsg" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th class="serial">#</th>
                                        <th style="display:none;">ID</th>
                                        <th>AVATAR</th>
                                        <th>EMP ID</th>
                                        <th>Role</th>
                                        <th>FIRST NAME</th>
                                        <th>LAST NAME</th>
                                        <th>MOBILE NO</th>
                                        <th>STATUS</th>
                                        
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="tableContents">
                                    <?php
                                    $count = 1;
                                    if (isset($result)) {
                                        foreach ($result as $row) {
                                            $jsonArray = json_encode(($row));
                                            // }
                                    ?>
                                            <tr>
                                                <td class="serial" data-id> <?php echo $count++ . "." ?></td>
                                                <td class="id" style="display:none;"> <?php echo $row["id"] ?> </td>
                                                <td class="year_from"> <span class="name"><?php echo $row["year_from"] ?></span> </td>
                                                <td class="year_to"> <span class="product"><?php echo $row["year_to"] ?></span> </td>
                                                <td class="status"><span class="name">
                                                        <?php echo $row["status"] == 1
                                                            ? "<a href='?type=status&operation=deactive&id=" . $row['id'] . "'>Active</a>"
                                                            : "<a href='?type=status&operation=active&id=" . $row['id'] . "'>Inactive</a>"
                                                        ?></span></td>
                                                <td>
                                                    <button class="edit btn btn-success" data-toggle="modal" data-target="#myModal" onclick=setModelValues('<?php echo $jsonArray; ?>')><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                    <!-- <button class="save btn btn-success" style="display:none;"><i class="fa fa-check" aria-hidden="true"></i></button> -->
                                                    <!-- <button class="cancel btn btn-danger" style="display:none;"><i class="fa fa-times" aria-hidden="true"></i></button> -->
                                                    <button class="del btn btn-warning" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
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