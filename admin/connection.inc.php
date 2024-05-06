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
    $result = $stmt->fetch();
    if($stmt->rowCount()>0){
        return $result;
    }
  }
  /**
   * function to select data from table
   */
  public function readData($qry, $params = []){
    $stmt = $this->conn->prepare($qry);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    if($stmt->rowCount()>0){
        return $result;
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
    $result = $stmt->fetchAll();
    return $stmt->rowCount();
  }
}


// try {
//     // echo $dsn;
//     $db = new PDO($dsn, $username, $password);
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     session_start();

// } catch (PDOException $e) {
//     echo 'Connection Failed!'.$e->getMessage();
//     //throw $th;
// }

?>