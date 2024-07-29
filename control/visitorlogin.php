<?php
// Include the connection file
require_once 'connection.php';

$username = $_POST['username'];;
$passwd = $_POST['password'];

// Retrieve image path from the database
$sql = "SELECT * FROM visiting_supervisor where username='$username' and password='$passwd'";
$result = $conn->query($sql);
while($row1 = $result->fetch_assoc()) {
  $id=$row1['id'];
}
if ($result->num_rows > 0) {
  session_start();
  $_SESSION['id']=$id; 

    echo '<!DOCTYPE html>
    <html lang="en" >
    <head>
      <meta charset="UTF-8">
      <title>CodePen - Loading Text Animation</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="../assets/dist/style.css">
    
    </head>
    <body>
    <div class="loading-container">
      <div class="loading-text">
        <span>L</span>
        <span>O</span>
        <span>A</span>
        <span>D</span>
        <span>I</span>
        <span>N</span>
        <span>G</span>
      </div>
    </div>
    </body>
    </html>';
    header('Location: ../visiting/students.php');
   }
   else{
    header('Location: ../visiting/?status=login_fail');
   }
?>