<?php
require_once '../connection.inc.php';
require_once '../utility/sessions.php';

class UnitController {
    private $dsn;
    private $username;
    private $password;
    private $output;

    public function __construct() {
        $this->dsn = "mysql:host=localhost;dbname=rkindustries;";
        $this->username = "root";
        $this->password = "";
        $this->output = "";
    }

    public function loadRecords() {
        try {
            $db = new dbConnector();
            $sql = "SELECT * FROM tbl_unit";
            $result = $db->readData($sql);
            $sr = 1;
            foreach ($result as $row) {
                $this->output .= "<tr>
                            <td>{$sr}</td>
                            <td>{$row["id"]}</td>
                            <td>{$row["unit"]}</td>
                            <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                            <td><button class='btn btn-danger unitDelete' data-id={$row["id"]}>Delete</button>
                                <button class='btn btn-info unitEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} >Edit</button></td>
                            </tr>";
                $sr++;
            }
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        echo $this->output;
    }

    public function insertRecord() {
        // Insert data into database
        
        if ($_POST['action'] == "insert") {
            try {
                $unit = $_POST['unitname'];
                $ustatus = $_POST['status'];
                // echo json_encode($_POST);
                // die();
                // $conn = new PDO($this->dsn, $this->username, $this->password);
                $db = new dbConnector();
                $sql = "select * from tbl_unit where unit = :unit";
                $params = ["unit"=>"$unit"];
                $result = $db->readSingleRecord($sql,$params);

                if (isset($result)) {
                    echo json_encode(array('duplicate' => true));
                } else {
                    // $sql = "insert into tbl_unit(unit,status) values('{$unit}','{$ustatus}')";
                    $sql = "insert into tbl_unit(unit,status) values(:unit,:ustatus)";
                    
                    $params = ["unit"=>$_POST["unitname"],"ustatus"=>$_POST["status"]];
                    $result = $db->insertData($sql,$params);
                    // echo $result;
                    // die();
                    // if ($conn->query($sql)) {
                    if ( $result)
                     {
                        echo json_encode(array('success' => true));
                    } else {
                        echo json_encode(array('success' => false));
                    }
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    public function deleteRecord() {
        // Delete data from database
        if ($_POST['action'] == "delete") {
            try {
                $id = $_POST['id'];
                $conn = new PDO($this->dsn, $this->username, $this->password);
                $sql = "delete from tbl_unit where id = '{$id}'";
                if ($conn->query($sql)) {
                    echo 1;
                } else {
                    echo 0;
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    public function editRecord() {
        // Model display with data for updation record
        if ($_POST['action'] == "edit") {
            try {
                $id = $_POST['id'];
                $conn = new PDO($this->dsn, $this->username, $this->password);
                $sql = "select * from tbl_unit where id  = {$id}";
                $result = $conn->query($sql);
                $this->output = "<div class='modal-dialog modal-dialog-centered'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                            <h4 class='modal-title'>Update Unit</h4>
                        </div>
                        <form action='' method='post' id='unitFormUpdata'>";
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $this->output .= "<div class='modal-body'>
                        <input type='hidden' id='unitId' name='id' value='{$row['id']}' />
                        <div class='form-group'>
                            <label for='unitname'>Unit</label>
                            <input class='form-control type='text' id='editunitname' name='unitname' value='{$row['unit']}' placeholder='Enter Unit' required>
                        </div>
                        <div class='form-group'>
                            <label for='Status'>Status</label>
                            <select class='form-control' name='status' id='editstatus' value='{$row['status']}>
                                <option value='' selected>Select</option>
                                <option value='1'>Active</option>
                                <option value='0'>Inactive</option>
                            </select>
                        </div>
                    </div>";
                }
                $this->output .= "</form>
                <!-- Modal footer -->
                <div class='modal-footer'>
                    <button type='submit' class='btn btn-primary btnUpdate' id='btnUpdate' data-id='update'>Update</button>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                </div>
                <div class='alert alert-dark' id='hmsg' style='display:none;'></div>
            </div>
        </div>";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            echo $this->output;
        }
    }

    public function updateRecord() {
        // Update record in database
        if ($_POST['action'] == "update") {
            try {
                $id = $_POST['id'];
                $conn = new PDO($this->dsn, $this->username, $this->password);
                $sql = "update tbl_unit set unit = '{$_POST['unit']}', status='{$_POST['status']}' where id  = {$id}";
                if ($conn->query($sql)) {
                    echo 1;
                } else {
                    echo 0;
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            echo $this->output;
        }
    }

    public function searchRecords() {
        // Search live record
        if ($_POST['action'] == "search") {
            try {
                $search_value = $_POST['search'];
                $conn = new PDO($this->dsn, $this->username, $this->password);
                $sql = "SELECT * FROM tbl_unit where unit like '%{$search_value}%'";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    $sr = 1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $this->output .= "<tr>
                                <td>{$sr}</td>
                                <td>{$row["id"]}</td>
                                <td>{$row["unit"]}</td>
                                <td>" . ($row['status'] == 1 ? 'Active' : 'Inactive') . "</td>
                                <td><button class='btn btn-danger unitDelete' data-id={$row["id"]}>Delete</button>
                                    <button class='btn btn-info unitEdit' data-toggle='modal' data-target='#myModal1' data-id={$row["id"]} >Edit</button></td>
                                </tr>";
                        $sr++;
                    }
                } else {
                    echo "Record not found";
                }
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
            echo $this->output;
        }
    }
}

// Instantiate the UnitController class
$unitController = new UnitController();

// Call methods based on the action
if(isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'load':
            $unitController->loadRecords();
            break;
        case 'insert':
            $unitController->insertRecord();
            break;
        case 'delete':
            $unitController->deleteRecord();
            break;
        case 'edit':
            $unitController->editRecord();
            break;
        case 'update':
            $unitController->updateRecord();
            break;
        case 'search':
            $unitController->searchRecords();
            break;
        default:
            // Handle other actions if needed
            break;
    }
}
?>
