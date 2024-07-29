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

if($_SERVER["REQUEST_METHOD"]=="POST"){
  $tokens=$_POST['tokens'];
  $link=$_POST['link'];
  $sql="insert into links (user,tokens) values('$id','$tokens')";
  if ($conn->query($sql) === TRUE) {  
      echo "<script>location.href='$link'</script>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
  //query selector for admin tokens
  $query = "SELECT * FROM admin where id= $id";
  $result = mysqli_query($conn, $query);
  while($row1= $result->fetch_assoc()) {
    $service=$row1['service_name'];
    $email=$row1['email'];
    $phone=$row1['phone'];
  }
  //query selector for admin tokens
  $query1 = "SELECT * from token" ;
  $result1 = mysqli_query($conn, $query1);

        //query selector for services
        $queryn = "SELECT * from service where admin=$id";
        $resultn = mysqli_query($conn, $queryn);  
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
    <a class="navbar-brand" href="#"><?php echo $email ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link " aria-current="page" href="../dashboard/">Dashboard</a>
        </li>
        <li class="nav-item"  style="cursor: pointer;">
          <a class="nav-link" data-bs-toggle="modal" data-bs-target="#login">Enroll Assistance</a>
        </li>
        <li class="nav-item"  style="cursor: pointer;">
          <a class="nav-link" data-bs-toggle="modal" data-bs-target="#assistance">Register Service</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../attended/">Attended Queues</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#">Add Tokens</a>
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
            <li><a class="dropdown-item" href="../home">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                <path d="M7.5 1v7h1V1z"/>
                <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
              </svg>
              Logout</a></li>
          </ul>
    </div>
  </div>
</nav>
</div><br>
<!--navbar ends-->





<!--table starts-->
<div class="container" style="margin-top: 1cm;">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Tokens</th>
        <th scope="col">Total Price</th>
        <th scope="col">Bonus (Tokens)</th>
        <th scope="col">Selection</th>
      </tr>
    </thead>
    <tbody>

    <?php
    $i=1;
    while($row=$result1->fetch_assoc()){
      $tokens=$row['tokens'];
      $link=$row['link'];
      $bonus=$row['bonus'];
      $price=$tokens*50;
echo '
      <tr>
        <th scope="row">'.$i.'</th>
        <td>'.$tokens.'</td>
        <td>'.number_format($price).' Tsh</td>
        <td> +'.$bonus.'</td>
        <td>
        <form method="post">
        <input type="hidden" name="tokens" value="'.$tokens.'">
        <input type="hidden" name="link" value="'.$link.'">
        <button class="btn btn-outline-success btn-sm" type="submit">Pay Now</button>
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
<!--footer ends--><!--Login starts-->  
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
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary">Enroll</button>
      </div>
      </form>
    </div>
  </div>
</div><br>
<!--customer enrollment ends-->
<!--login ends-->
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
        <button type="button" class="btn btn-outline-success" onclick="window.location.href='index.php'">Close</button>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" onclick="window.location.href='index.php'">Close</button>
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
</div><br>
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

const alertTrigger = document.getElementById('liveAlertBtn')
if (alertTrigger) {
  alertTrigger.addEventListener('click', () => {
    appendAlert('Link Copied!', 'success')
  })
}
    </script>
     <script>
      function attend(){
        var attended=document.getElementById("customers");
        var newattend = parseInt(attended.innerText);
       document.getElementById("customers").innerHTML=newattend+1;

       var attended=document.getElementById("unattended");
        var newattend = parseInt(attended.innerText);
        if(newattend>0){
       document.getElementById("unattended").innerHTML=newattend-1;
      }else{
      document.getElementById("unattended").innerHTML=newattend;
    }
    }
    </script>
</html>