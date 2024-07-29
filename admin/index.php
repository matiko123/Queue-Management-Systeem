<?php
//initializing database connection.
include '../control/connection.php';
//session holding the coordinator logged in
session_start();
if(!isset($_SESSION['admin'])){
    header('Location : ../index.php');
}else{
    $id=$_SESSION['admin'];
}



$link="https://ifmstudent2024.000webhostapp.com/fyp/user/?service_id=".$id;
$qrcode=rand(999,100);
  if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['action'])){
    $action =$_POST['action'];
    if($action==='tokens'){
      $customer=$_POST['customer'];
    $sql="update admin set token=token-1 where id=$id";
    if ($conn->query($sql) === TRUE) { 
      $current_time= date('Y-m-d H:i:s'); 
      $sql1="update customer set attendance=1,attended_time='$current_time' where id=$customer";
      if ($conn->query($sql1) === TRUE) {
        $sql2="update customer set attendance=attendance+1 where attendance>1 and service=$id";
        $conn->query($sql2);
        $sql3="update customer set attendance=0 ,calls=calls+1 where attendance=5 and service=$id";
        $conn->query($sql3);
        header('Location: ./');
        exit();
      }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

}elseif($action==='linkrequest'){
    $tokensrequested=$_POST['tokensrequested'];
    $sql="insert into links(user,tokens) values('$id','$tokensrequested')";
    if ($conn->query($sql) === TRUE) {
      header('Location: ./request_success');
      exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}elseif($action==='enrollment'){
  $phone=$_POST['phone'];
  $name=$_POST['name'];
  $service=$_POST['service_name'];
  $sql="insert into assistance(username,phone,password,service) values('$name','$phone','assistance','$service')";
  if ($conn->query($sql) === TRUE) {
    header('Location: ./');
    exit();
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}elseif($action==='postpone'){
  $customer=$_POST['customer'];
  $sql1="update customer set attendance=2 where id=$customer";
  if($conn->query($sql1)==TRUE){
    header('Location: ./');
    exit();
  }; 
}elseif($action==='service'){
  $service_name=$_POST['service_name'];
  $sql="insert into service(admin,service_name) values('$id','$service_name')";
  if ($conn->query($sql) === TRUE) {
    header('Location: ./');
    exit();
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
  }

  //query selector for admin tokens
  $query1 = "SELECT * FROM admin where id= $id";
  $result1 = mysqli_query($conn, $query1);
  while($row1= $result1->fetch_assoc()) {
    $service=$row1['service_name'];
    $phone=$row1['phone'];
    $tokens=$row1['token'];
    $email=$row1['email'];
    $tokens_enabler="";
    if($tokens<=0){
      $tokens=0;
      $tokens_enabler="disabled";
      echo "<script>alert('No tokens available Please add tokens!');</script>";
    }
  }

    //query selector for customers enrolled
    $query2 = "SELECT * from customer where attendance=0 and service=$id and calls<3";
    $result2 = mysqli_query($conn, $query2);

        //query selector for unattended customers
        $query3 = "SELECT count(id) as unattended from customer where attendance=0 and service=$id";
        $result3 = mysqli_query($conn, $query3);
        while($row= $result3->fetch_assoc()) {
          $unattended=$row['unattended'];
        }
               //query selector for unattended customers
               $query4 = "SELECT count(id) as attended from customer where attendance=1 and service=$id";
               $result4 = mysqli_query($conn, $query4);
               while($row= $result4->fetch_assoc()) {
                 $attended=$row['attended'];
               }

      //query selector for services
      $queryn = "SELECT * from service where admin=$id";
      $resultn = mysqli_query($conn, $queryn);
      $resultm = mysqli_query($conn, $queryn);
?>
<html>
    <head>
        <title>Queue Management System</title>
        <link rel="stylesheet" href="../css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
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
    <a class="navbar-brand" href="#"><?php echo $email?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
        </li>
        <li class="nav-item" style="cursor: pointer;">
          <a class="nav-link"  data-bs-toggle="modal" data-bs-target="#login">Enroll Assistance</a>
        </li>
        <li class="nav-item"  style="cursor: pointer;">
          <a class="nav-link" data-bs-toggle="modal" data-bs-target="#assistance">Register Service</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../attended/">Attended Queues</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../requests/">Add Tokens</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../history/">Tokens History</a>
        </li>
        <li class="nav-item dropdown" >
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
           Profile
          </a>
           <ul class="dropdown-menu">
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profile">Profile Info</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#change">Change Password</a></li>
            <li><a class="dropdown-item" href="../../home">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                <path d="M7.5 1v7h1V1z"/>
                <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
              </svg>
              Logout</a>
            </li>
          </ul>
    </div>
  </div>
</nav>
</div><br>
<!--navbar ends-->

<!--customer attendance and usrname starts-->
<div class="container text-center">
  <div class="row">
    <div class="col-auto me-auto"></div>
    <div class="col-auto">
      Available Tokens :&nbsp; <span class="shadow-lg  badge text-danger bg-success-subtle" style="font-size: x-large;"><?php echo $tokens ?></span>

    </div>
  </div>
</div><br>
<div class="container text-center">
  <div class="row">
    <div class="col-auto me-auto">Unattended &nbsp; <span class="shadow-lg  badge text-danger bg-success-subtle" style="font-size: x-large;" id="unattended"><?php echo $unattended ?> </span></div>
    <div class="col-auto">
     Attended  :&nbsp; <span class="shadow-lg  badge text-danger bg-success-subtle" style="font-size: x-large;" id="customers"><?php echo $attended?> </span>
    </div>
  </div>
</div><br>
<!--customer attendance and usrname ends-->
  <div class="container text-center">
    <div class="row">
      <div class="col-auto me-auto ">
      <button class="btn btn-outline-primary  fw-bold"  role="button" onclick="qrBtn()"  >Get QR Image</button>
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-primary " type="button" onclick="location.href='../requests/'">
          Add Token
        </button>
      </div>
    </div>
  </div>

<!--alert starts -->
<div class="container"><div id="liveAlertPlaceholder" style="font-size: 14px;"></div></div>
<!--alert ends -->
  <!--Positioning of the form to right-->
<div class="container text-center">
  <div class="row">
    <div class="col-auto me-auto" >
    </div>
    <div class="col-auto">
      <!--token filling form-->
      <div style="min-height: 120px;">
    <div class="collapse " id="addtoken">
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
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Phone</a></th>
        <th scope="col">Attendance</th>
        <th scope="col">Postpone</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i=1;
          while($row= $result2->fetch_assoc()) {
            $customer_id=$row['id'];
            $name=$row['name'];
            $phone=$row['phone'];
            if($i==1){
              $button='<button class="btn btn-outline-success btn-sm '.$tokens_enabler.'" onclick="attend()" type="submit">Attend</button></td>';
              $enable='<button class="btn btn-outline-danger btn-sm '.$tokens_enabler.'"  type="submit">Postpone</button>';
            }else{
              $button='<label class="text-secondary"><i>Pending...</i></label>';
              $enable='<button class="btn btn-outline-danger btn-sm disabled"  type="submit">Postpone</button>';
            }
            echo '
             <tr>
        <th scope="row">'.$i.'</th>
        <td>'.$name.'</td>
        <td>'.$phone.'</td>
        <td>
        <form method="post" action="">
          <input type="hidden" name="action" value="tokens" >
          <input type="hidden" name="customer" value="'.$customer_id.'" >
          '.$button.'
        </form>
        
        <td>
       <form method="post" action="">
          <input type="hidden" name="action" value="postpone" >
          <input type="hidden" name="customer" value="'.$customer_id.'" >
          '.$enable.'
        </form>
        </td>
      </tr>
            ';
            $i++;
          }
          ?>
    </tbody>
  </table>
</div>
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
<!--customer enrollment starts-->  
<div class="modal fade" id="login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Enroll an assistance</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <input type="hidden" name="action" value="enrollment" >
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Assistance Phone:</label>
            <input type="number" class="form-control" id="recipient-name" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Assistance Name</label>
            <input type="text" class="form-control" id="recipient-name" name="name" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Service</label>
            <select class="form-select" name="service_name" required>
                    <?php
            $i=1; 
             while($row= $resultm->fetch_assoc()) {
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
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary">Enroll</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--customer enrollment ends-->
<!--service registry starts-->  
<div class="modal fade" id="assistance" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Registe a new service</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
        <input type="hidden" name="action" value="service" >
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Service Name :</label>
            <input type="text" class="form-control" id="recipient-name" name="service_name" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Register</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--service registry ends-->
<!--profile starts-->  
<div class="modal fade" id="profile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Profile Info :</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Service name: <?php echo $service ?> </label>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Customer's Phone: <?php echo $phone ?> </label>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Customer's Email: <?php echo $email ?> </label>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success"  data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div><br>
<!--profile ends-->
<!--password change starts-->  
<div class="modal fade" id="change" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Change password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter old password</label>
            <input type="password" class="form-control" id="recipient-name" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Enter new password</label>
            <input type="password" class="form-control" id="recipient-name" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Confirm new password</label>
            <input type="password" class="form-control" id="recipient-name" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" onclick="window.location.href='index.php'">Update</button>
      </div>
    </div>
  </div>
</div>
<button id="requestSuccess" style="display:none">

<!--password change ends-->
    </body>
    <script>
      const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const appendAlert = (message, type) => {
  const wrapper = document.createElement('div')
  wrapper.innerHTML = [
    `<div class="alert alert-${type} alert-dismissible" role="alert">`,
    `   <div>${message}</div>`,
    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
    '</div>'
  ].join('')

  alertPlaceholder.append(wrapper)
}
function qrBtn(){
    appendAlert('Qr-code downloaded ✔ .Check in the download list!.', 'success')
    var qr = new QRious({
                value: '<?php echo $link ?>'
            });
            var link = document.createElement('a');
            link.href = qr.toDataURL();
            link.download = '<?php echo $link?>.png';
            link.click();
  }

const tokenTrigger = document.getElementById('requestSuccess')
if (tokenTrigger) {
  tokenTrigger.addEventListener('click', () => {
    appendAlert('Tokens requested successfully!', 'success')
  })
}
    </script>

</html>