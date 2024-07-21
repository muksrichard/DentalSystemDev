<?php
include 'includes/config.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != "admin") {
    # code...
    header("location:login.php");
}

$userid = $_SESSION['userid'];
// getting current logged in suupport id 
$cs = $_SESSION['email'];
// echo $cs;

$sql0 = "SELECT id,email FROM users WHERE email='$cs'";
$stmt0 = $conn->prepare($sql0);
$stmt0->execute();
$result0 = $stmt0->get_result();
while ($row0 = $result0->fetch_assoc()) {
    $inserted_by = $row0['email'];
    $inserted_by_id = $row0['id'];
    //echo $row0['id']; 
}


include('includes/aheader.php');
include('includes/anavbar.php');
?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <div class="badge badge-secondary text-center col-md-4">
                <!-- Topbar Search -->
                <h3 class="h3 mb-0 text-white-800"> Add User</h3>
            </div>

            <!-- Topbar Navbar -->

        </nav>

        <div class="container">

            <?php
            //js to refresh the page and remove the parameter from the url unsetting the session
            if (isset($_GET['a_success'])) { ?>
                <div class="row">
                    <div class="col-lg-12 alert alert-success alert-dismissible text-center">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <b>User Successfully added! Go to Users page to for more details </b>
                    </div>
                </div>
                <script>
                    window.history.pushState('page2', 'Title', 'addusers.php');
                </script>
            <?php } ?>

            <div class="row justify-content-center">

                <div class="col-12 col-md-10 col-lg-8"><?php include('includes/taction.php'); ?>


                    <form method="post" enctype="multipart/form-data" target="hidden_iframe" class="card card-sm">

                        <input type="hidden" name="inserted_by" value=<?= $inserted_by_id; ?>>

                        <div class="col">

                            <div class="card-body row no-gutters align-items-center">
                                <h5 class="text text-gray-800">Full Name :</h5>
                                <input class="form-control form-control-borderless text-dark" type="text" name="fullname" value="<?php echo $fullname; ?>" required>
                                <h5 class="text text-gray-800">Date of Birth:</h5>
                                <input class="form-control form-control-borderless text-dark" type="date" name="dateofbirth" value="<?php echo $dateofbirth; ?>" required>

                                <h5 class="text text-gray-800">Phone Number:</h5>
                                <input class="form-control form-control-borderless text-dark" type="text" name="phonenumber" value="<?php echo $phonenumber; ?>" required>

                                <h5 class="text text-gray-800">Email :</h5>
                                <input class="form-control form-control-borderless text-dark" type="email" name="email" required>
                                <h5 class="text text-gray-800">Password :</h5>
                                <input class="form-control form-control-borderless text-dark" type="password" name="password" required>

                                <h6 class="text text-gray-800">Role:</h6>
                                <select name="user_type" class="form-control" id="exampleFormControlSelect1" required>
                                    <option value="admin">Admin </option>
                                    <option value="reception">Reception </option>
                                    <option value="dentist">Dentist </option>
                                    <option value="patient">Patient </option>
                                </select>

                                <div class="col-md-12 p-2">
                                    <button class="btn btn-lg btn-primary" type="submit" name="add_user">Add</button>
                                </div>

                            </div>
                            <p id="successMsg" class="text text-success"></p>

                        </div>
                    </form>


                </div>
            </div>
        </div>



    </div>

    <!-- End of Page Wrapper -->

    <?php include('includes/scripts.php');
    #include('includes/footer.php'); 
    ?>


    <!-- Footer -->
    <footer class="sticky-footer bg-white">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Dental System 2023</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->
</body>

</html>