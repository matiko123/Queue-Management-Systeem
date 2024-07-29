<?php
//query selector for admin tokens and service name
include "../control/connection.php";

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['action'])){
  $action =$_POST['action'];
  if($action==='delete'){
  $linkid=$_POST['linkid'];
  $sql="update links set status=2 where id = $linkid";
  if ($conn->query($sql) === TRUE) {  
      echo "<script>alert('link number $linkid rejected');</script>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

}elseif($action==='payment'){
  $tokens=$_POST['tokens'];
  $bonus=$_POST['bonus'];
  $link=$_POST['link'];
  $sql="insert into token (tokens,bonus,link) values('$tokens','$bonus','$link')";
  if ($conn->query($sql) === TRUE) {
    header('Location: links.php');
    exit();
}
}elseif($action==='approve'){
  $linkid=$_POST['linkid'];
  $tokens=$_POST['tokens'];
  $customer_id=$_POST['customer_id'];
  $sql="update links set status=1 where id=$linkid";
  if ($conn->query($sql) === TRUE) {
    $query="update admin set token=token+$tokens where id=$customer_id";
    $conn->query($query);
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}
}
}

$query1 = "SELECT links.tokens as tokens,links.user,links.id as linkid ,links.user as customer_id,admin.* from links inner join admin on admin.id=links.user where links.status=0";
$result1 = mysqli_query($conn, $query1);
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
          <a class="nav-link" aria-current="page" href="home.php">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="#" >Requested Payments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="https://merchant.azampay.co.tz/home">Link Portal</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#paymentlink">Generate Payments</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="links.php">Payments Generated</a>
        </li>
        <li class="nav-item dropdown" >
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
           Profile
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profile">Profile Info</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#change">Change Password</a></li>
            <li><a class="dropdown-item" href="../management/?welcome">
              <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-power" viewBox="0 0 16 16">
                <path d="M7.5 1v7h1V1z"/>
                <path d="M3 8.812a5 5 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812"/>
              </svg>
              Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</div><br>
<!--navbar ends-->
<!--profile starts-->
<div class="container">
<div class="container text-center" style="margin-top: 10px;">
  <div class="row">
    <div class="col-auto me-auto shadow rounded"  style="background-color: whitesmoke">Requested Tokens</div>
    <div class="col-auto"></div>
  </div>
</div>
</div><br>
<!--profile ends-->
<!--table starts-->
<div class="container" style="">
<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Service Name</th>
      <th scope="col">Tokens</th>
      <th scope="col">Phone</th>
      <th scope="col">Email</th>
      <th scope="col">Approval</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $i=1;
    while($row1= $result1->fetch_assoc()) {
      $service=$row1['service_name'];
      $phone=$row1['phone'];
      $linkid=$row1['linkid'];
      $email=$row1['email'];
      $tokens=$row1['tokens'];
      $customer_id=$row1['customer_id'];
       echo '
       <tr>
      <th scope="row">'.$i.'</th>
      <td>'.$service.'</td>
      <td>'.$tokens.'</td>
      <td>'.$phone.'</td>
      <td>'.$email.'</td>
      <form method="post">
      <input type="hidden" name="action" value="approve">
      <input type="hidden" name="tokens" value="'.$tokens.'">
      <input type="hidden" name="linkid" value="'.$linkid.'">
      <input type="hidden" name="customer_id" value="'.$customer_id.'">
      <td><button class="btn btn-outline-primary" type="submit">Approve</button>
      </form>
      </tr> 
      
       ';
       $i++;
    }
    ?>
    
  </tbody>
</table>
  </div>
</div>
<!--table ends-->
 <!--Link portal starts-->  
 <div class="modal fade" id="paymentlink" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Generate Payment Link</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="">
        <input type="hidden" name="action" value="payment" >
         <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Tokens</label>
            <input type="number" class="form-control" id="recipient-name" name="tokens" required>
            <label for="recipient-name" class="col-form-label">Bonus</label>
            <input type="number" class="form-control" id="recipient-name" name="bonus" required>
            <label for="recipient-name" class="col-form-label">Payment Link</label>
            <input type="text" class="form-control" id="recipient-name" name="link" required>
          </div> 
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-outline-primary" >Generate</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!--link portal ends-->
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
        <h1 class="modal-title fs-5" id="exampleModalLabel">Enroll a customer</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <input type="hidden" name="action" value="enrollment" >
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Customer's Phone:</label>
            <input type="number" class="form-control" id="recipient-name" name="phone" required>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Customer's Name</label>
            <input type="text" class="form-control" id="recipient-name" name="name" required>
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
            <label for="recipient-name" class="col-form-label">Customer's Phone: 0711223344</label>
          </div>
          <div class="mb-3">
            <label for="recipient-name" class="col-form-label">Customer's Name: Lorem Ipsum</label>
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
</html>