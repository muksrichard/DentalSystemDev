<?php
include 'controllers/authController.php';

if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
  $role = $_SESSION['role'];
  if ($role == "reception") {
    # code...
    header("location:add.php");
    // header("location:sindex.php");
  } elseif ($role == "admin") {
    # code...
    header("location:admin.php");
  } elseif ($role == "dentist") {
    # code...
    header("location:windex.php");
  } elseif ($role == "patient") {
    # code...
    header("location:patient.php");
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Dental System - Login</title>

  <!--Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/style.min.css" rel="stylesheet">
  <style type="text/css">
    .custom-centered {
      margin: 0 auto;
      width: 1000px;
    }

    .wrapper {
      margin: 0 auto;
    }
  </style>
</head>

<body class="bg-gradient-light custom-centered">

  <div class="wrapper col-lg-9">


    <div class="card border-0 shadow-lg card-body p-0 my-5 mx-5 ">

      <div class="col-lg-12">
        <div class="p-5">
          <div class="text-center">
            <!-- <img src="img/gator-logo.png" width="150" height="100"> -->
            <h1 class="py-3 h4 text-gray-900 mb-4">Welcome Back to Hope Dental Clinic! </h1>
          </div>
          <form action="login.php" method="post" class="user">
            <div class="form-group p-1">
              <?php foreach ($msg as $msgs) : ?>

                <?php echo validation_errors($msgs);  ?>

              <?php endforeach; ?>
              <?php display_message(); ?>
            </div>
            <div class="form-group p-1">
              <input type="email" name="email" class="form-control" placeholder="Enter Email" required>
            </div>
            <div class="form-group p-1">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="form-group p-1">

              <input type="submit" name="login-submit" class="btn btn-primary btn-block" value="Login">
          </form>
          <hr>

        </div>
      </div>

    </div>


  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/resource/js/resource.bundle.min.js"></script>

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>