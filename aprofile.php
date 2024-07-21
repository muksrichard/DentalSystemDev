<?php
include 'includes/config.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != "admin") {
    header("location:login.php");
}
$update = false;
$id = "";
$fullname = "";
$email = "";
$password = "";
$phonenum = "";
$otherphonenum = "";
$mpesaphonenum = "";
$closephonenum = "";
$closephonenum = "";
$bankaccountnum = "";
$bankname = "";
$profilepic = "";

//code for profile button
if (isset($_GET['profile'])) {
    $id = $_GET['profile'];
    if ($_SESSION['userid'] == $id) {

        $query = "SELECT * FROM users WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $id = $row['id'];
        //$username = $row['username'];
        $fullname = $row['fullname'];
        $password = $row['password'];
        $email = $row['email'];
        $phonenum = $row['phonenum'];
        // $otherphonenum = $row['otherphonenum'];
        // $mpesaphonenum = $row['mpesaphonenum'];
        // $closephonenum = $row['closephonenum'];
        // $bankaccountnum = $row['bankaccountnum'];
        // $bankname = $row['bankname'];
        $profilepic = $row['profilepic'];
        //$profilepic=$_FILES['profilepic']['name'];
        //$upload="../uploads/".$profilepic;
        $update = true;
    } else
        $idormsg = "You cannot view other user's details ...nice try :)";
}

//code to update records in db
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    //$username=$_POST['username'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phonenum = $_POST['phonenum'];
    // $otherphonenum = $_POST['otherphonenum'];
    // $mpesaphonenum = $_POST['mpesaphonenum'];
    // $closephonenum = $_POST['closephonenum'];
    // $bankaccountnum = $_POST['bankaccountnum'];
    // $bankname = $_POST['bankname'];
    //$profilepic=$_FILES['profilepic']['name'];
    $upload = "../uploads/" . $profilepic;


    // $query = "UPDATE users SET fullname=?,email=?,password=?,phonenum=?,otherphonenum=?,mpesaphonenum=?,closephonenum=?,bankaccountnum=?,bankname=? WHERE id=?";
    $query = "UPDATE users SET fullname=?,email=?,password=?,phonenum=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $fullname, $email, $password, $phonenum, $id);
    $stmt->execute();
    $_SESSION['response'] = "Updated Succesfully!";
    $_SESSION['res_type'] = "primary";
    header("location:profile.php?profile=$id");
}
?>

<?php
include('includes/aheader.php');
include('includes/anavbar.php');
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <v id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>


            <div class="badge badge-dark p-3 col-md-6">
                <!-- Topbar Search -->
                <h3 class="h3 mb-0 text-white-800 "> My Details </h3>

            </div>
            <!-- Topbar Search -->
            <?php
            if (isset($_SESSION['response'])) { ?>
                <div class="alert alert-<?= $_SESSION['res_type']; ?> alert-dismissible text-center">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <b><?= $_SESSION['response']; ?></b>
                </div>
            <?php }
            unset($_SESSION['response']); ?>




            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                    </div>
                </li>

                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter">3+</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">
                            Alerts Center
                        </h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 12, 2019</div>
                                <span class="font-weight-bold">A new monthly report is ready to download!</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-donate text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 7, 2019</div>
                                $290.29 has been deposited into your account!
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">December 2, 2019</div>
                                Spending Alert: We've noticed unusually high spending for your account.
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                    </div>
                </li>

                <!-- Nav Item - Messages -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-envelope fa-fw"></i>
                        <!-- Counter - Messages -->
                        <span class="badge badge-danger badge-counter">7</span>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                        <h6 class="dropdown-header">
                            Message Center
                        </h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                                <div class="status-indicator bg-success"></div>
                            </div>
                            <div class="font-weight-bold">
                                <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                                <div class="small text-gray-500">Emily Fowler · 58m</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="https://source.unsplash.com/AU4VPcFN4LE/60x60" alt="">
                                <div class="status-indicator"></div>
                            </div>
                            <div>
                                <div class="text-truncate">I have the photos that you ordered last month, how would you like them sent to you?</div>
                                <div class="small text-gray-500">Jae Chun · 1d</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="https://source.unsplash.com/CS2uCrpNzJY/60x60" alt="">
                                <div class="status-indicator bg-warning"></div>
                            </div>
                            <div>
                                <div class="text-truncate">Last month's report looks great, I am very happy with the progress so far, keep up the good work!</div>
                                <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="dropdown-list-image mr-3">
                                <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60" alt="">
                                <div class="status-indicator bg-success"></div>
                            </div>
                            <div>
                                <div class="text-truncate">Am I a good boy? The reason I ask is because someone told me that people say this to all dogs, even if they aren't good...</div>
                                <div class="small text-gray-500">Chicken the Dog · 2w</div>
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <h6><?= $_SESSION['email'] ?></h6>
                        </span>
                        <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="earnings.php">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            My earnings
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>


        <script src="./js/verify.js"></script>
        <div class="container">
            <div class="container" id="notifysupport">
                <?php
                //file that has the notifications div
                //include('notify_support.php');
                ?>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">

                    <form action="profile.php" method="post" enctype="multipart/form-data" class="user">
                        <input type="hidden" name="id" value="<?= $id; ?>">
                        <div class="col">
                            <?php if (isset($idormsg)) {
                                echo $idormsg;
                            } ?>

                            <div class="card-body row no-gutters align-items-center">
                                <?php
                                /*  // Loading users with support role
                                            $sql1 = "SELECT * FROM users WHERE user_type='support' AND id='$id'";
                                            $stmt1=$conn->prepare($sql1);
                                            $stmt1->execute();
                                            $result1=$stmt1->get_result();
                                            while($row1=$result1->fetch_assoc()){ ?>  */
                                ?>
                                <h5>Username:</h5>
                                <input class="form-control form-control-user" value="<?= $username; ?>" type="text" name="username" required>

                                <h5>Password:</h5>
                                <input class="form-control form-control-user" value="<?= $password; ?>" type="text" name="password" required>

                                <h5>Full Name:</h5>
                                <input class="form-control  form-control-user " value="<?= $fullname; ?>" type="text" name="fullname" required>
                                <h5>Email:</h5><input class="form-control  form-control-user" value="<?= $email; ?>" type="text" name="email" required>

                                <h5> Phone Number:</h5><input class="form-control form-control-borderless" value="<?= $phonenum; ?>" type="text" name="phonenum" required>
                                <div class="row">





                                    <div class="col-md-6">
                                        <hr>
                                        <div class="col-md-6">
                                            <h5>Update Profile pic:</h5>
                                            <input class="btn" type="file" value="<?= $profilepic; ?>" name="profilepic" id="file">
                                            <img src="<?= $profilepic; ?>" width="120" class="file-thumbnail">
                                        </div>
                                        <?php //} 
                                        ?>
                                    </div>
                                    <div class="user col-md-12">

                                    </div>


                                    <div class="user col-md-12">
                                        <?php if ($update == true) { ?>
                                            <input type="submit" name="update" class="btn btn-success btn-block" value="Update Profile">
                                        <?php } else { ?>
                                            <input type="submit" name="add" class="btn btn-primary btn-block" value="Update Profile">
                                        <?php } ?>
                                    </div>

                                </div>

                            </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.forms[0].to.Focus();
        </script>
        <!-- /.container-fluid -->


        <!-- End of Main Content -->


        <!-- End of Content Wrapper -->

</div>

<!-- End of Page Wrapper -->

<?php include('includes/scripts.php');
#include('includes/footer.php'); 
?>

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Dental System </span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->


</body>

</html>