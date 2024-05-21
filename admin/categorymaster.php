<?php
require('../admin/template/top.inc.php');
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">PRODUCT CATEGORY MASTER</h3>
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
                                            <h4 class="modal-title">Add Product Category</h4>
                                        </div>
                                        <!-- Modal body -->
                                        <form action="" method="post">
                                            <div class="modal-body">

                                                <input type="hidden" id="modalid" name="id" value="" />
                                                <div class="form-group">
                                                    <label for="category">Product Category</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter category" id="categoryname" name="categoryname" required>
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
                                            <button type="submit" class="btn btn-primary modalsubmit" id="btnSave" data-id="save">Submit</button>
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
                                        <th>CATEGORY</th>
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
<?php require('../admin/template/footer.inc.php') ?>
<script src="assets/js/custom.js" type="text/javascript"></script>
</body>
</html>