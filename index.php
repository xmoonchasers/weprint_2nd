
<?php 
include 'Includes/dbcon.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>(AMS) Attendance Monitoring System - Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-login" style="background-image: url('img/logo/loral1.jpe00g');">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <div class="text-center">
                    <img src="data:image/png;base64,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" style="width:75px;height:75px">
                    <img src="data:image/png;base64,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" style="width:65px;height:65px">

                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">ATTENDANCE MONITORING SYSTEM</h1>
                  </div>
                  <form class="user" method="Post" action="">
                 
                    <div class="form-group">
                      <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                      <input type="password" name = "password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <!-- <label class="custom-control-label" for="customCheck">Remember
                          Me</label> -->
                      </div>
                    </div>
                    <div class="form-group">
                        <input type="submit"  class="btn btn-success btn-block" value="Login" name="login" />
                    </div>
                     </form>

<?php

  if(isset($_POST['login'])){

    $userType = "";
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = md5($password);

    $Aquery = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
    $rs = $conn->query($Aquery);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();

    if($num > 0){
      $userType = "Administrator";
    }

    $Squery = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password'";
    $rs = $conn->query($Squery);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();

    if($num > 0){
      $userType = "ClassTeacher";
    }


    if($userType == "Administrator"){

      $query = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['Id'];
        $_SESSION['firstName'] = $rows['firstName'];
        $_SESSION['lastName'] = $rows['lastName'];
        $_SESSION['emailAddress'] = $rows['emailAddress'];

        echo "<script type = \"text/javascript\">
        window.location = (\"Admin/index.php\")
        </script>";
      }

      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }
    else if($userType == "ClassTeacher"){

      $query = "SELECT * FROM tblclassteacher WHERE emailAddress = '$username' AND password = '$password'";
      $rs = $conn->query($query);
      $num = $rs->num_rows;
      $rows = $rs->fetch_assoc();

      if($num > 0){

        $_SESSION['userId'] = $rows['Id'];
        $_SESSION['firstName'] = $rows['firstName'];
        $_SESSION['lastName'] = $rows['lastName'];
        $_SESSION['emailAddress'] = $rows['emailAddress'];
        $_SESSION['classId'] = $rows['classId'];
        $_SESSION['classArmId'] = $rows['classArmId'];

        echo "<script type = \"text/javascript\">
        window.location = (\"ClassTeacher/index.php\")
        </script>";
      }

      else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

      }
    }
    else{

        echo "<div class='alert alert-danger' role='alert'>
        Invalid Username/Password!
        </div>";

    }
}
?>

                    <!-- <hr>
                    <a href="index.html" class="btn btn-google btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="index.html" class="btn btn-facebook btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a> -->

                
                  <div class="text-center">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>