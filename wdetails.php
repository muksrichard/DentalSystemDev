<?php
include 'includes/config.php';

session_start();
$cs = $_SESSION['email'];
$role = $_SESSION['role'];
if ((!isset($_SESSION['email']) && $_SESSION['role'] != "dentist") || (!isset($_SESSION['email']) && $_SESSION['role'] != "admin")) {
    # code...
    header("location:login.php");
}

$sql0 = "SELECT email,id FROM users WHERE email='$cs'";
$stmt0 = $conn->prepare($sql0);
$stmt0->execute();
$result0 = $stmt0->get_result();
while ($row0 = $result0->fetch_assoc()) {
    $inserted_by = $row0['email'];
    $inserted_by_id = $row0['id'];
    //echo $row0['id']; 
}
if (isset($_GET['details'])) {
    $id = $_GET['details'];
    // Loading users with writer role
    //$sql = "SELECT * FROM users WHERE user_type='writer'";
    $sql = "SELECT appointment.id,users.fullname ,users.id AS pid,
  appointment.activity, appointment.appointmentdate FROM appointment INNER JOIN users
  ON users.id=appointment.user_id  
  WHERE appointment.send=1 AND appointment.id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $patient_ids =  $row['pid'];
    $patient_names =  $row['fullname'];
    $activity = $row['activity'];
    //   $juserame = $row['juserame'];


}
if (isset($_POST['update'])) {
    $consult_time = date("Y-m-d H:i:s");
    $diagnosis = $_POST['diagnosis'];;
    $treatment = $_POST['treatment'];
    $patient_id = $_POST['patient_id'];
    $staff_id = $_POST['inserted_by_id'];
    $consult = 1;
    $query = "INSERT INTO consultation(constltation_date,diagnosis,treatment,patient_id,staff_id,consult)VALUES(?,?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $consult_time, $diagnosis, $treatment, $patient_id, $staff_id, $consult);
    $stmt->execute();
    $_SESSION['response'] = "Updated Succesfully!";
    $_SESSION['res_type'] = "primary";
    header('location:prescription.php');
}

?>

<?php
if (isset($_SESSION['email']) && $_SESSION['role'] == "dentist") {
    include('includes/writer-header.php');
    include('includes/writer-nav.php');
}
if (isset($_SESSION['email']) && $_SESSION['role'] == "admin") {
    include('includes/aheader.php');
    include('includes/anavbar.php');
}


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

            <div class="badge badge-primary text-center col-md-4">
                <!-- Topbar Search -->
                <h3 class="h3 mb-0 text-white-800">Consultation Details</h3>
            </div>

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
                        <a class="dropdown-item" href="wearnings.php">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Earnings
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


        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8">
                    <form action="wdetails.php" method="post" enctype="multipart/form-data" class="card card-sm">
                        <input type="hidden" name="inserted_by_id" value=<?= $inserted_by_id; ?>>
                        <input type="hidden" name="patient_id" value=<?= $patient_ids; ?>>

                        <div class="col">

                            <div class="card-body row no-gutters align-items-center">

                                <h5>Patient Name:</h5>
                                <div class="col-md-12">
                                    <input class="col-md-12 text text-primary  form-control form-control-borderless" value="<?= $patient_names ?>" type="text"></input>
                                </div>
                                <h5>Possible Tratement:</h5>
                                <div class="col-md-12">
                                    <h4 class="col-md-12 text text-primary  form-control form-control-borderless"><?= $activity; ?></h4>
                                </div>

                                <h5>Diagnosis:</h5>
                                <div class="col-md-12">
                                    <textarea class="col-md-12 text text-dark  form-control form-control-borderless" name="diagnosis"></textarea>
                                </div>
                                <h5>Treatment:</h5>
                                <div class="col-md-12">
                                    <textarea class="col-md-12 text text-dark  form-control form-control-borderless" name="treatment">

                                    </textarea>
                                </div>

                                <div class="p-2">

                                    <button class="btn btn-success p-2" type="submit" name="update">Update Consultation session</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


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
                <span>Copyright &copy; Dental Systems</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            width: 640,
            toolbar: [
                ['style', []],
                ['font', []],
                ['color', []],
                ['para', []],
                ['table', []],
                ['insert', []],
                ['view', ['fullscreen']]
            ]
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            cache: false
        });
        setInterval(function() {
            var detailsid = <?php echo $id; ?>;
            var url = "wdetails.php?details=" + detailsid;
            $('#notify').load(url + " #notify").fadeIn('slow');
        }, 5000);
    });
</script>
</body>

</html>