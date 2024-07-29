<?php
include "../control/connection.php";
session_start();
if(!isset($_SESSION['customer'])){
  header('Location : ../index.php');
}else{
  $id=$_SESSION['customer'];
} 


$customers='';
  $callsdiv='display:none;';
  $positiondiv="display:block;";
  $query = "SELECT customer.*,service.service_name as service_name,customer.service_name as service_number  FROM customer inner join service on customer.service_name=service.id where customer.id=$id" ;
  $result = mysqli_query($conn, $query);
  while($row= $result->fetch_assoc()) {
    $service=$row['service_name'];
    $service_number=$row['service_number'];
    $name=$row['name'];
    $phone=$row['phone'];
    $email=$row['email'];
    $service_id=$row['service'];
    $calls=$row['calls'];
    if($calls==3){
      $calls='<label class="text-danger" style="font-size:20px">Queue Terminated!</label>';
      $callsdiv='display:block;';
      $positiondiv="display:none;";
    }if($calls>1 || $calls!=3){
      $calls='<label class="text-danger" style="font-size:20px">Queue Terminated!</label>';
    }
  }

  $query1 = "SELECT count(id) as position from customer where id<=$id and attendance=0 and calls<3 and service='$service_id' and service_name=$service_number" ;
  $result1 = mysqli_query($conn, $query1);
  while($row= $result1->fetch_assoc()) {
    $position=$row['position'];
    $pos=$row['position'];

    if($position==1){
      $position='<label class="text-success" style="font-size:20px">Please visit service window </label>';
    }

    if($position==0){
     $position='<label class="text-success" style="font-size:20px">Your satisfaction is our priority!<br> 🥰</label>';
    }
  }

  $query2 = "SELECT count(id) as customers from customer where attendance=0 and calls<3 and service='$service_id'" ;
  $result2 = mysqli_query($conn, $query2);
  while($row= $result2->fetch_assoc()) {
    $customers=$row['customers'];
  }

  if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['action'])){
    $action =$_POST['action'];
if($action==='newqueue'){
  $sql="insert into customer(service,name,phone,email) values('$service_id','$name','$phone','$email')";
  if ($conn->query($sql) === TRUE) {
    $sql1="delete from customer where service=$service_id and name='$name' and phone ='$phone' and email='$email' and calls=3";
    if($conn->query($sql1)==TRUE){
      $query = "SELECT * from customer where service=$service_id and name='$name' and phone ='$phone' and email='$email' order by id desc limit 1" ;
      $result = mysqli_query($conn, $query);
      while($row= $result->fetch_assoc()) {
        $_SESSION['customer']=$row['id'];
    };
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
 
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
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" >
        <meta http-equiv="refresh" content="10">
        <link rel="stylesheet" href="../css/style.css">
        <style>
          .footer{
            font-size:10px!important;
          }
        </style>
    </head>
    <body class="bg-secondary-subtle">


<!--navbar starts -->
<div class="container">
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">QMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../history/">Queue History</a>
          </li>
          <li class="nav-item dropdown" >
            <a class="nav-link" href="../history/?support">Customer Support</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  </div><br>
  <!--navbar ends-->

<!--profile and logout-->
<div class="container">
<div class="container text-center" style="margin-top: 10px;">
  <div class="row">
    <div class="col-auto me-auto shadow rounded"  style="background-color: whitesmoke">User : <?php echo $name ?></div>
    <div class="col-auto"></div>
  </div>
</div>
</div><br>
<div class="container text-center">
  <div class="row">
    <div class="col-auto me-auto"></div>
    <div class="col-auto"><span class="shadow-lg badge  bg-success-subtle" style="font-size: large;margin-top:-1.3cm"><button class="btn btn-outline-danger" id="muteButton" onclick="toggleMute()">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-volume-mute" viewBox="0 0 16 16">
  <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06M6 5.04 4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96zm7.854.606a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0"/>
</svg>
    </button></span>
    </div>
  </div>
</div><br>
<!--profile and logout ends-->
<!--Heading starts-->
<div class="container text-center" >
  <div class="row justify-content-start">
    <div class="col-4">
    </div>
    <div class="col-4 shadow rounded " style="background-color: whitesmoke;margin-top: 10px;">
    <label class="text-dark" style="font-size:20px"><?php echo strtoupper($service)?></label>
    </div>
  </div><br>

<!--Heading ends-->


<!--Position-->
<div class="container text-center">
  
  <div class="row">
    <div class="col"></div>
    <div class="col order-5"></div>
    <div class="col order-1">
        <div class="card-body text-primary">
          <h1 class="card-title shadow p-3 mb-5 bg-body-tertiary rounded" style="font-size: 120px;<?php echo $positiondiv ?>" id="position"><?php echo $position ?></h1>
        </div>
        <div class="card-body text-primary">
          <h1 class="card-title shadow p-3 mb-5 bg-body-tertiary rounded" style="font-size: 120px;<?php echo $callsdiv ?>"><?php echo $calls ?></h1>
        </div>
        <div class="card-body text-primary">
           <h4 class="card-title shadow p-3 mb-5 bg-body-tertiary rounded" style="<?php echo $callsdiv ?>">Available Customers
            <br>
           <p style="font-size: 80px;"> <?php echo $customers?></p>
           </h4>
        </div>
        <form method="post">
        <input type="hidden" name="action" value="newqueue" >
          <button type="submit" style="border:transparent;background-color:transparent"> <h1 type="submit" class="card-title shadow p-3 mb-5 btn btn-outline-success rounded" style="font-size: 18px;display: block;<?php echo $callsdiv?>">Request New Queue</h1></button>
        </form>
      </div>
  </div>
</div>
<!--Position ends-->
<button data-bs-toggle="modal" data-bs-target="#sorry" id="sorrybutton" style="display: none;"></button>

<!--footer starts -->
<div class="fixed-bottom bg-warning-subtle">
  <div class="container text-center">
    <div class="row" >
      <div class="col order-last">
        <h6 class="footer">2024 IFM Students</h6>
      </div>
      <div class="col">
        <h6 class="footer" >Designed And Developed By </h6>
      </div>
      <div class="col order-first">
        <h6 class="footer" >&copy;All right Reserved</h6>
      </div>
    </div>
  </div>
</div>
<!--footer ends-->
<audio id="audio1" src="../../audio/1.wav"></audio>
<audio id="audio2" src="../../audio/2.wav"></audio>
<audio id="audio3" src="../../audio/3.wav"></audio>
<audio id="audio4" src="../../audio/4.wav"></audio>
<audio id="audio5" src="../../audio/5.wav"></audio>
<script>
    document.addEventListener('DOMContentLoaded',(event)=>
    {
    let hey = document.getElementById("position").innerHTML;
    if(<?php echo $pos ?>==5){
    audio5.play();
    }else if(<?php echo $pos ?>==4){
      audio4.play();
    }else if(<?php echo $pos ?>==3){
      audio3.play();
    }else if(<?php echo $pos ?>==2 ){
      audio2.play();
    }else if(<?php echo $pos ?>==1){
      audio1.play();
    }
  })
  </script>

<script>
            document.addEventListener('DOMContentLoaded', () => {
            const isMuted = localStorage.getItem('isMuted') === 'true';
            const audios = document.querySelectorAll('audio');
            audios.forEach(audio => audio.muted = isMuted);
            
        });

        function toggleMute() {
            const audios = document.querySelectorAll('audio');
            const isMuted = !audios[0].muted;
            audios.forEach(audio => audio.muted = isMuted);
            localStorage.setItem('isMuted', isMuted);
            muteButton.classList="btn btn-danger";
        }

    </script>

    </body>
</html>