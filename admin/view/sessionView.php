<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';
$db = new dbConnector();
$username = checkUserSession();
$editRecord = [];


if (isset($_GET['type']) && !empty($_GET['type'])) {
    $type = $_GET['type'];
    $operation = $_GET['operation'];
    $id = $_GET['id'];
    if ($type == 'status') {
        $status = $operation == 'active' ? 1 : 0;
        $sql = "update financial_years set status=:status where id=:id";
        $params = ["status" => $status, "id" => $id];
        $rows = $db->ManageData($sql, $params);
    }
}

$sql = "select * from financial_years order by year_from desc";
$result = $db->readData($sql);
?>
<?php
require('../template/top.inc.php');
?>
<div class="content pb-0">
    <div class="orders">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">FINANCIAL YEARS</h3>
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
                                            <button type="button" class="close modalClose" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Add Financial Year</h4>
                                        </div>
                                        <!-- Modal body -->
                                        <form action="" method="post">
                                            <div class="modal-body">

                                                <input type="hidden" id="modalid" name="id" value="" />
                                                <div class="form-group">
                                                    <label for="yearFrom">Year From</label>
                                                    <input class="form-control yearlimit modalyearfrom" type="number" min="1900" max="2100" step="1" placeholder="From Year" id="year_from" name="year_from" width="200%" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="yearTo">Year to</label>
                                                    <input class="yearlimit form-control modalyearto" type="number" min="1900" max="2100" step="1" placeholder="Year To" id="year_to" name="year_to" readonly tabindex="-1" required>
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
                                            <button type="button" class="btn btn-secondary modalClose" data-dismiss="modal">Close</button>
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
                                        <th>Year from</th>
                                        <th>Year to</th>
                                        <th>Status</th>
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
<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="../assets/js/financialyear.js" type="text/javascript"></script>
</body>
</html>