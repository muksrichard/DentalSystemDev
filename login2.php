<?php
include_once('includes/constants.php');
session_start();
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
  }
}
$conn = new mysqli(Constants::DEV_URL, Constants::DB_USERNAME, Constants::DB_PASWORD, Constants::DB_NAME);
$msg = "";

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT id,username,password,user_type FROM users WHERE username=? AND password=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  if ($row['user_type'] == "admin" || $row['user_type'] == "reception" || $row['user_type'] == "dentist") {
    session_regenerate_id();
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['user_type'];
    $_SESSION['userid'] = $row['id'];
    $_SESSION['writerid'] = $row['id'];
    // $_SESSION['status'] = $row['disabled'];
    $_SESSION['status'] = 0;
    session_write_close();
  }

  if ($result->num_rows == 1 && $_SESSION['role'] == "reception" && $_SESSION['status'] == 0) {
    header("location:add.php");
  } else if ($result->num_rows == 1 && $_SESSION['role'] == "dentist" && $_SESSION['status'] == 0) {
    header("location:windex.php");
  } else if ($result->num_rows == 1 && $_SESSION['role'] == "admin" && $_SESSION['status'] == 0) {
    header("location:admin.php");
  } else if ($result->num_rows == 1 && $_SESSION['role'] == "reception" && $_SESSION['status'] == 1) {
    $msg = "Account disabled Contact administrator";
  } else if ($result->num_rows == 1 && $_SESSION['role'] == "writer" && $_SESSION['status'] == 1) {
    $msg = "Account disabled Contact administrator";
  } else if ($result->num_rows == 1 && $_SESSION['role'] == "admin" && $_SESSION['status'] == 1) {
    $msg = "Account disabled Contact administrator";
  } else {
    $msg = "Incorrect username or password";
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
          <form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" class="user">
            <div class="form-group p-1">
              <input type="text" name="username" class="form-control" placeholder="Enter Username" required>
            </div>
            <div class="form-group p-1">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <div class="alert-dismissible text-dark p-1">
              <h5><?= $msg; ?></h5>
            </div>
            <div class="form-group p-1">

              <input type="submit" name="login" class="btn btn-primary btn-block" value="Login">
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