<?php
// Include the connection file
require_once 'connection.php';

$phone = $_POST['phone'];;
$passwd = $_POST['password'];

// Retrieve image path from the database
$sql = "SELECT * FROM admin where phone='$phone' and password='$passwd'";
$result = $conn->query($sql);
while($row1 = $result->fetch_assoc()) {
  $id=$row1['id'];
}
if ($result->num_rows > 0) {
  session_start();
  $_SESSION['admin']=$id; 

    header('Location: ../admin/dashboard/');
   }
   else{
    header('Location: ../?status=login_fail');
   }
?>