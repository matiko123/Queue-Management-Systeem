<?php
include "../control/connection.php";
session_start();
$_SESSION['service_id']=$_GET['service_id'];
if(!isset($_SESSION['service_id'])){
  header('Location : ../index.php');
}else{
  $service_id=$_SESSION['service_id'];
}
if(!isset($_SESSION['otp'])){
  $_SESSION['otp']="none";
};
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//required files
require '../mailer/src/Exception.php';
require '../mailer/src/PHPMailer.php';
require '../mailer/src/SMTP.php';
 if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['action'])){
  $action =$_POST['action'];
  if($action==='mailing'){
    $title="Queue Management System";
    $name=$_POST['name'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $service_name=$_POST['service_name'];
    $otp=rand(100000,999999);
    $_SESSION['otp']=$otp;
    $message="Your Verification code is : ".$otp;
    $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();                              //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;             //Enable SMTP authentication
    $mail->Username   = 'christianstephen025@gmail.com';   //SMTP write your email
    $mail->Password   = 'oktbibdggmoktqmi';      //SMTP password
    $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
    $mail->Port       = 465;                                    

    //Recipients
    $mail->setFrom("christianstephen025@gmail.com", $title); // Sender Email and name
    $mail->addAddress($email);     //Add a recipient email  
    $mail->addReplyTo($email, "QMS user"); // reply to sender email

    //Content
    $mail->isHTML(true);               //Set email format to HTML
    $mail->Subject = "Verification Code ";   // email subject headings
    $mail->Body    = $message; //email message

    // Success sent message alert
    $mail->send();
    $sql="insert into customer(service,name,phone,email,service_name) values('$service_id','$name','$phone','$email','$service_name')";
    $conn->query($sql);
    $query1 = "SELECT * from customer where phone='$phone' and service_name='$service_name' and email='$email' order by id desc" ;
    $result1 = mysqli_query($conn, $query1);
    while($row= $result1->fetch_assoc()) {
      $customer=$row['id'];
   }

    echo"<script>location.href='../user/?mailed&service_id=".$_SESSION['service_id']."&customer=".$customer."&service=".$service_name."'</script>";

}elseif($action==='verification'){
   $verification=$_POST['verification_code'];
   if($verification==$_SESSION['otp']){
    $_SESSION['customer']=$_GET['customer'];
     echo"<script>location.href='home/'</script>";
 }
}
 }

       //query selector for services
       $queryn = "SELECT * from service where admin=$service_id";
       $resultn = mysqli_query($conn, $queryn);
?>
<html>
    <head>
        <title>Queue Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body class="bg-secondary-subtle">

<button data-bs-toggle="modal" data-bs-target="#login" id="loginbutton" style="display: none;">login</button>

<!--Login starts-->  
<div class="modal fade " id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow bg-body-tertiary rounded">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Specific Service Name</h1>
      </div>
      <div class="modal-body">
        <form method="post">
          <input type="hidden" name="action" value="mailing">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter Your Full Name: </label>
            <input type="text" class="form-control" id="recipient-name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter Your Phone Number: </label>
            <input type="number" class="form-control" id="recipient-name" name="phone" placeholder="25512345678" required>
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Select Service: </label>
            <div class="mb-3">
                    <select class="form-select" name="service_name" required>
                    <?php
            $i=1; 
             while($row= $resultn->fetch_assoc()) {
              $service_type=$row['id'];
              $service_name=$row['service_name'];
              echo'   
                            <option value="'.$service_type.'">'.$i.'. '.$service_name.'</option>
                  ';
                  $i++;
             }
                  ?>
                    </select>
                </div>
          </div>    
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter Your Email: </label>
            <input type="email" class="form-control" id="recipient-name" placeholder="abcd@gmail.com" name="email" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Proceed</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<button id="mailed" style="display:none;" data-bs-toggle="modal" data-bs-target="#otp"></button>
<!--login ends-->
<!--Otp starts-->  
<div class="modal fade " id="otp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow bg-body-tertiary rounded">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Let's Verify if it's You! </h1>
      </div>
      <div class="modal-body">
    <form method="post">
      <input type="hidden" name="action" value="verification">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter the OPT sent on your Email :</label>
            <input type="text" class="form-control" id="recipient-name" name="verification_code" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Request A Queue</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--footer starts -->
<div class="fixed-bottom bg-warning-subtle">
  <div class="container text-center">
    <div class="row">
      <div class="col order-last">
        <h6 class="footer">&copy;2024 IFM Students</h6>
      </div>
      <div class="col">
        <h6 class="footer">Designed And Developed By </h6>
      </div>
      <div class="col order-first">
        <h6 class="footer">All right Reserved</h6>
      </div>
    </div>
  </div>
</div>
<!--footer ends-->
<!--Otp ends-->

<script>
       if(window.location.href.includes("user/?service")){
            function doneclick(){
              document.getElementById("loginbutton").click();
            }
            doneclick();
           }
           if(window.location.href.includes("user/?mailed")){
            function doneclick(){
              document.getElementById("mailed").click();
            }
            doneclick();
           }
</script>
    </body>
</html>