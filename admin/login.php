<?php
require_once '../admin/connection.inc.php';
$db = new dbConnector();
$msg = "";
// echo $_POST['submit'];
if(isset($_POST['submit'])){
   $username = $_POST['username'];
   $password = $_POST['password'];
   if(!empty($username) && !empty($password)){
    try{
      $sql = "select * from login where user_name=:username and password=:password";
      $params = ["username"=>$username,"password"=>$password];
      $userExists = $db->isDataExists($sql,$params);
      // $stmt= $db->prepare($sql);
      // $stmt->execute(["username"=>$username,"password"=>$password]);
      if($userExists === true){
         session_start();
         $_SESSION['username'] = $username;
         $_SESSION['islogin'] = true;
         print_r($_SESSION);
         header("location:index.php");
      }
      else{
         $msg = 'login failed! Enter valid username and password';
      }
       
    } 
    catch(PDOException $e){
      echo 'data error'.$e->getMessage();
    } 
   }
}
   
?>

<!doctype html>
<html class="no-js" lang="">
   <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Login Page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="assets/css/normalize.css">
      <link rel="stylesheet" href="assets/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/font-awesome.min.css">
      <link rel="stylesheet" href="assets/css/themify-icons.css">
      <link rel="stylesheet" href="assets/css/pe-icon-7-filled.css">
      <link rel="stylesheet" href="assets/css/flag-icon.min.css">
      <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
      <link rel="stylesheet" href="assets/css/style.css">
      <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
   </head>
   <body class="bg-dark">
      <div class="sufee-login d-flex align-content-center flex-wrap">
         <div class="container">
            <div class="login-content">
               <div class="login-form mt-150">
                  <form method="post">
                     <div class="form-group">
                        <label>User Name</label>
                        <input type="text" name ="username" class="form-control" placeholder="User Name">
                     </div>
                     <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                     </div>
                     <button type="submit" name="submit" class="btn btn-success btn-flat m-b-30 m-t-30">Sign in</button>
                     <div class="errmss"><?php echo $msg?></div>
					</form>
               </div>
            </div>
         </div>
      </div>
      <script src="assets/js/vendor/jquery-2.1.4.min.js" type="text/javascript"></script>
      <script src="assets/js/popper.min.js" type="text/javascript"></script>
      <script src="assets/js/plugins.js" type="text/javascript"></script>
      <script src="assets/js/main.js" type="text/javascript"></script>
   </body>
</html>