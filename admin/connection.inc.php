<?php
class dbConnector{
  private  $servername = 'localhost';
  private  $dbname = 'rkindustries';
  private  $username = 'root';
  private  $password = '';
  private $conn;

  public function __construct(){
    $this->conn = $this->connect();
  }
  public function connect(){
    $db = new PDO("mysql:host=$this->servername;dbname=$this->dbname",$this->username,$this->password);
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // session_start();
    return $db;
  }
  /**
   * function to select data from table
   */
  public function readSingleRecord($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if($stmt->rowCount()>0){
        return $result;
    }
    return null;
  }
  /**
   * function to select data from table
   */
  public function readData($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if($stmt->rowCount()>0){
        return $result;
    } else {
      return(NULL);
    }
  }
  /**
   * function to confirm that data exists or not
   */
  public function isDataExists($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $stmt->rowCount()>0;
  }
  /**
   * function to insert, update and delete in table
   */
  public function insertData($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    return $this->conn->lastInsertId();
  }
  /**
   * function to insert, update and delete in table
   */
  public function ManageData($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $stmt->rowCount();
  }
  /**
   * function to get total count of rows in table
   */
  public function CountData($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total_records'];
  }
  /**
   * function to get record id
   */
  public function getID($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($result)){
      return $result['id'];
    }
    return 0;
  }
  /**
   * function to log user actions
   */
  public function log_user_action($user_id, $action, $table_name, $record_id, $details = null, $previous_data = null) {
    $db = $this->connect();
    $qry = "INSERT INTO user_actions_log (user_id, action, table_name, record_id, details, previous_data) VALUES (:user_id, :action, :table_name, :record_id, :details, :previous_data)";
    $params = ["user_id"=>$user_id, "action" => $action,"table_name"=>$table_name,"record_id"=>$record_id, "details"=> $details, "previous_data"=>$previous_data];
    $lastInsertedId = $this->insertData($qry, $params);  
  }

  public function get_buttons_permissions($params){
    // print_r($params);
    $sql = "select * from tbl_user_permissions where userid=:userid and moduleid=:moduleid";
    $data = $this->readSingleRecord($sql, $params);
    $permission_values = [0=>'disabled',1=>''];
    $permissions = [];
    if(isset($data)){
        $permissions["insert"]=$permission_values[$data["insert_record"]];
        $permissions["update"]=$permission_values[$data["update_record"]];
        $permissions["delete"]=$permission_values[$data["delete_record"]];
    }
    return $permissions;
  }

}
?>