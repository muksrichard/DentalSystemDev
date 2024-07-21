<?php
@ob_start();
require 'vendor/autoload.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dental System - Register</title>

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
                        <h1 class="py-3 h4 text-gray-900 mb-4">Create an account below </h1>
                    </div>
                    <form method="post" class="user">
                        <?php include('controllers/authController.php'); ?>

                        <div class="form-group p-1">
                            <input type="text" class="form-control" name="first_name" value="<?php echo $first_name; ?>" placeholder="Full Name" required>
                        </div>
                        <div class="form-group p-1">
                            <input type="text" class="form-control" name="email" placeholder="Email Address" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="form-group p-1">
                            <input type="text" name="phonenumber" value="<?php echo $phonenumber; ?>" class="form-control" placeholder="Phone number" required>
                        </div>
                        <div class="form-group p-1">
                            <input type="date" name="dateofbirth" class="form-control" placeholder="Date of birth" required>
                        </div>
                        <div class="form-group p-1">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group p-1">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                        </div>

                        <div class="form-group p-1">

                            <input type="submit" name="signup-btn" class="btn btn-primary btn-block" value="Register">
                    </form>
                    <hr>
                    <div class="alert-dismissible text-dark p-1">
                        <a href="login.php"></a>
                    </div>
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