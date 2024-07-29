<?php
// Include the connection file
require_once 'connection.php';

$phone = $_POST['phone'];;
$passwd = $_POST['password'];

// Retrieve image path from the database
$sql = "SELECT * FROM assistance where phone='$phone' and password='$passwd'";
$result = $conn->query($sql);
while($row1 = $result->fetch_assoc()) {
  $id=$row1['id'];
}
if ($result->num_rows > 0) {
  session_start();
  $_SESSION['assistance']=$id; 
header('Location: ../assistance/index.php');
   }
   else{
    echo "failre";
header('Location: ../assistance/login.php?status=login_fail');
   }
?>