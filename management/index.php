<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $email=$_POST['email'];
  $password=$_POST['password'];
  if ($email=="management@gmail.com" && $password=="management") {  
      echo "<script>location.href='home.php'</script>";
  } else {
    echo "<script>location.href='?welcome'</script>";
  }
}
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Management Login</h1>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter Your Email: </label>
            <input type="email" class="form-control" id="recipient-name" name="email" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter Your Password: </label>
            <input type="password" class="form-control" id="recipient-name" name="password"  required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Login</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<button id="mailed" style="display:none;" data-bs-toggle="modal" data-bs-target="#otp"></button>
<!--login ends-->

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
       if(window.location.href.includes("management/?welcome")){
            function doneclick(){
              document.getElementById("loginbutton").click();
            }
            doneclick();
           }
</script>
    </body>
</html>