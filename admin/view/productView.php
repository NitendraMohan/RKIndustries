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

$sql = "Select id,category_name from tbl_category where status=1";
$categories = $db->readData($sql);

$sql = "Select id,unit from tbl_unit where status=1";
$units = $db->readData($sql);
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
                                    <h4 class="modal-title">Add Product</h4>
                                </div>
                                <!-- Modal body -->
                                <form action="" method="post" id="userForm">
                                    <div class="modal-body">

                                        <input type="hidden" id="modalid" name="modalid" value="" />
                                        <div class="form-group">
                                            <label for="image">Select Product Image</label>    
                                            <input class="form-control" type="file" name="image" id="image">
                                        </div>
                                        <img src="" alt="logo image" id="logo_image" name="logo_image" onerror="this.onerror=null; this.src='../images/favicon.png'" height="20%" width="20%"/>    
                                        <div class="form-group">
                                            <label for="category">Select Category</label>
                                            <select class="form-control modalyearstatus" name="category" id="category">
                                                <option value="" selected>Select..</option>
                                                <?php foreach($categories as $category){
                                                    echo "<option value='{$category['id']}'>{$category['category_name']}</option>";
                                                 }?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="subcategory">Select Sub Category</label>
                                            <select class="form-control modalyearstatus" name="subcategory" id="subcategory">
                                                <option value="" selected>Select..</option>
                                                <?php foreach($subcategories as $subcategory){
                                                    echo "<option value='{$subcategory['id']}'>{$subcategory['subcategory_name']}</option>";
                                                 }?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="productname">Product Name</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Product Name" id="productname" name="productname"  required>
                                        </div>
                                        <div class="form-group">
                                            <label for="unit">Select Unit</label>
                                            <select class="form-control modalyearstatus" name="unit" id="unit">
                                                <option value="" selected>Select..</option>
                                                <?php foreach($units as $unit){
                                                    echo "<option value='{$unit['id']}'>{$unit['unit']}</option>";
                                                 }?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Enter Price</label>
                                            <input class="form-control yearlimit modalyearfrom" type="text" placeholder="Enter Price" id="price" name="price" required>
                                        
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
                                        <button type="button" class="btn btn-secondary modalClose" id="btnClose" data-dismiss="modal">Close</button>
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
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th>Product Name</th>
                                        <th>Unit</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                        <th>STATUS</th>
                                        <th NOWRAP>USER ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="tableContents" id="productsTableContents">

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
<script src="../assets/js/productmaster.js" type="text/javascript"></script>
</body>

</html>