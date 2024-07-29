<?php
require_once "control/connection.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $phone=$_POST['phone'];
  $service=$_POST['service'];
  $password=$_POST['password'];
  $email=$_POST['email'];
  $sql="insert into admin(service_name,phone,email,password) values('$service','$phone','$email','$password')";
  if ($conn->query($sql) === TRUE) {  
    header('Location: ?status=registered');
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
<html>
    <head>
        <title>Queue Management System</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <!----------------------------------------->
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/fonts/fontawesome5-overrides.min.css">
        <!------------------------------------------------>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    </head>
    <body class="container bg-secondary-subtle ">

<!--welcome starts-->
          <div class="card text-center">
            <div class="card-header">
             Queue Management System - QMS
            </div>
            <div class="card-body background">
              <h5 class="card-title">Stay In Line where ever you are !</h5>
              <button  class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#login" id="log" style="display:none">Login</button>
              <button  class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#loginfail" id="logfail" style="display:none">Login</button>
              <p class="card-text ">Avoid line jumpers, avoid quarrel in competing for service position. Let everyone enjoy the service.</p>
              <a href="#" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#readmore">Read More</a>
              <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#login" >Get Started</button>
            </div>

          </div>

<!--welcome ends-->
<!--register starts-->  
<div class="modal fade" id="signup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Sign Up For Free !</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Organization Name :</label>
            <input type="text" class="form-control" id="recipient-name" name="service" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Phone Number :</label>
            <input type="text" class="form-control" id="recipient-name" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Email :</label>
            <input type="email" class="form-control" id="recipient-name" name="email" required>
          </div>

          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Create Password :</label>
            <input type="password" class="form-control" id="recipient-name" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Confirm Password :</label>
            <input type="password" class="form-control" id="recipient-name" name="password" required>
          </div>
          <button type="submit" class="btn btn-outline-primary">Sign Up Now</button>
        </form>
      </div>
    </div>
  </div>
</div><br>
<!--register ends-->
<!--Login starts-->  
<div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registered User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="control/adminlogin.php">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Phone:</label>
            <input type="number" class="form-control" id="recipient-name" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Password :</label>
            <input type="password" class="form-control" id="recipient-name" name="password" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-success" >Login</button>  
      </div>
      </form>
      <div class="modal-footer">
      <label for="recipient-name" class="col-form-label">Have No account?! : &nbsp;<a href="#" class="text-primarty"  data-bs-toggle="modal" data-bs-target="#signup">Sign Up</a></label>
    </div>  
    </div>
  </div>
</div><br>
<!--login ends-->
<!--Login fail starts-->  
<div class="modal fade" id="loginfail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog" >
    <div class="modal-content" >
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel" style="color:red!important">Wrong Credentials Please Try again</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="control/adminlogin.php">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Phone:</label>
            <input type="number" class="form-control" id="recipient-name" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Your Password :</label>
            <input type="password" class="form-control" id="recipient-name" name="password" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Login</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--login fail ends-->
<!--Read More starts-->
<div class="collapse " id="readmore">
  <div class="card card-body background">
    <p class="container">
    Queue management system it is a web application that will help organize and control the flow of customers, 
    it will help businesses or organizations manage their queue efficiently, reduce waiting times, 
    and will help improver customers satisfaction by minimizing waiting time and reducing queue lengths during peak business hours. 
  </p>
  <p class="container">
The system will allow customers/users to wait with their mobile devices the customers will reserve an appointment online 
then the system will assign ti Featuredckets with unique numbers to customers directing them to appropriate service points and only 
checking in at the service center when they are ready to be serviced  because the system will provide real time updates on waiting 
time and about their turn through SMS or in -app notification to be called forward for the service 
</p>
  </div>
</div>
<!--read more ends-->
<!--footer starts -->
<div style="margin-top: 2cm;"></div>
<div class="fixed-bottom bg-warning-subtle" >
  <div class="container text-center">
    <div class="row">
      <div class="col order-last" >
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
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/theme.js"></script>
<script>
         if(window.location.href.indexOf('registered') !== -1) {
       log.click();
      }if(window.location.href.indexOf('login_fail') !== -1){
        logfail.click();
      }
    </script>
    </body>
</html>