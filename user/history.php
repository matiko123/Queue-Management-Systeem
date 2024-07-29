<?php
include "../control/connection.php";

session_start();
$id=$_SESSION['customer'];

$query1 = "SELECT * from customer where id=$id" ;
$result1 = mysqli_query($conn, $query1);
while($row= $result1->fetch_assoc()) {
  $name=$row['name'];
  $phone=$row['phone'];
}
  $query = "SELECT customer.*,service.service_name as service FROM customer inner join admin on admin.id=customer.service inner join service on service.admin=admin.id where  customer.phone=$phone order by id desc" ;
  $result = mysqli_query($conn, $query);


  if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['action'])){
    $action =$_POST['action'];
if($action==='inquiry'){
  $title=$_POST['title'];
  $comments=$_POST['comments'];
  $sql="insert into comments (phone,title,comments) values ('$phone','$title','$comments')";
  $conn->query($sql);
  header('Location: ./');
  exit();
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
            <a class="nav-link" aria-current="page" href="../home/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">Queue History</a>
          </li>
          <li class="nav-item dropdown" >
            <a class="nav-link" data-bs-toggle="modal" data-bs-target="#support">Customer Support</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  </div><br>
  <!--navbar ends-->
<!--Inquiry starts-->  
<div class="modal fade" id="support" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Send us a Query</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <input type="hidden" name="action" value="inquiry" >
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Title:</label>
            <input type="text" class="form-control" id="recipient-name" name="title" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Brief descriptions</label>
            <textarea type="text" class="form-control" id="recipient-name" name="comments" required style="height:3cm"></textarea>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary">Send</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--Inquiry ends-->
<!--profile starts-->
<div class="container">
<div class="container text-center" style="margin-top: 10px;">
  <div class="row">
    <div class="col-auto me-auto shadow rounded"  style="background-color: whitesmoke">User : <?php echo $name ?></div>
    <div class="col-auto"></div>
  </div>
</div>
</div><br>
<!--profile ends-->


  <!--Positioning of the form to right-->
<div class="container text-center">
  <div class="row">
    <div class="col-auto me-auto" >
      <div class="collapse " id="getlink">
        <div class="card card-body " style="width: 150px;">
        <img src="qr.png" class="img-fluid" alt="..." id="qrimage">
        <input type="text" id="linkInput" value="https://admin.userlink.queue" style="font-size: 10px;" readonly>
        <div class="modal-footer">
        <a href="#" class="btn btn-outline-secondary" id="liveAlertBtn" onclick="document.getElementById('linkInput').select();document.execCommand('copy');qrimage.style.display='none';liveAlertBtn.style.display='none';linkInput.style.display='none';">copy link</a>
      </div>
      <div id="liveAlertPlaceholder" style="font-size: 10px;width: 3.5cm;"></div>
    </div>
    </div>
    </div>
    <div class="col-auto">
      <!--token filling form-->
      <div style="min-height: 120px;">
  <div class="collapse " id="addtoken">
    <div class="card card-body " style="width: 300px;">

</div>
    </div>
  </div>
</div>
    </div>
  </div>
</div>
<!--token ends-->
<!--table starts-->
<div class="container" style="margin-top: -3cm;">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">ID </th>
        <th scope="col">Service</th>
        <th scope="col">Status</th>
        <th scope="col">Attended time</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $i=1;
    while($row=$result->fetch_assoc()){
      $service=$row['service'];
      $status=$row['attendance'];
      $time=$row['attended_time'];
      if($status==0){
        $status='<label class="text-primary">Unattended</label>';
      }elseif($status==1){
        $status='<label class="text-success">Attended</label>';
      }elseif($status==2){
        $status='<i><label class="text-danger">Pending...</label></i>';
      }

      if($time==''){
        $time="...";
      }
echo '
      <tr>
        <th scope="row">'.$i.'</th>
        <td>'.$service.'</td>
        <td>'.$status.'</td>
        <td>'.$time.'</td>
      </tr>
      ';
      $i++;
    }
    ?>
    </tbody>
  </table>
</div>
<button id="supporter" style="display: none"  data-bs-toggle="modal" data-bs-target="#support">
<!--table ends-->
<!--footer starts -->
<div class="fixed-bottom bg-warning-subtle">
  <div class="container text-center">
    <div class="row">
      <div class="col order-last">
        <h6 class="footer">2024 IFM Students</h6>
      </div>
      <div class="col">
        <h6 class="footer">Designed And Developed By </h6>
      </div>
      <div class="col order-first">
        <h6 class="footer">&copy;All right Reserved</h6>
      </div>
    </div>
  </div>
</div>
<!--footer ends-->
<script>
      if(window.location.href.indexOf('support') !== -1) {
       document.getElementById('supporter').click();
      }
</script>  



    </body>
</html>