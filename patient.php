<?php
include 'includes/config.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != "patient") {
    # code...
    header("location:login.php");
}

$userid = $_SESSION['userid'];
// getting current logged in suupport id 
$cs = $_SESSION['email'];
// echo $cs;

$inserted_by_id = "";

$sql0 = "SELECT id,email FROM users WHERE email='$cs'";
$stmt0 = $conn->prepare($sql0);
$stmt0->execute();
$result0 = $stmt0->get_result();
while ($row0 = $result0->fetch_assoc()) {
    $inserted_by = $row0['email'];
    $inserted_by_id = $row0['id'];
    //echo $row0['id']; 
}

// Loading users with patient role
$sql1 = "SELECT id,fullname FROM users WHERE user_type='dentist'";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$result1 = $stmt1->get_result();

include('includes/pheader.php');
include('includes/pnavbar.php');
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
                <h3 class="h3 mb-0 text-white-800"> Add Appointment</h3>
            </div>
            <div class="badge badge-secondary text-center col-md-4">
                <!-- Topbar Search -->
                <h3> <a class="h3 mb-0 text-white" href="pprofile.php?profile=<?= $userid;
                                                                                ?>">My profile</a> </h3>
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
                        <b>Record Successfully added! Go to Schedule page for more details</b>
                    </div>
                </div>
                <script>
                    window.history.pushState('page2', 'Title', 'add.php');
                </script>
            <?php } ?>

            <div class="row justify-content-center">

                <div class="col-12 col-md-10 col-lg-8"><?php include('includes/taction.php'); ?>


                    <form method="post" enctype="multipart/form-data" target="hidden_iframe" class="card card-sm">

                        <input type="hidden" name="inserted_by_id" value=<?= $inserted_by_id; ?>>

                        <div class="col">

                            <div class="card-body row no-gutters align-items-center">
                                <h6 class="col-md-12 p-1">Today is: <?= date("jS M  g:i a"); ?></h6>
                                


                                <h5 class="text text-gray-800">Choose Dentist  :</h5>
                                <select name="dentist_id" class="form-control" id="exampleFormControlSelect1" required>
                                    <?php

                                    while ($row1 = $result1->fetch_assoc()) { ?>
                                        <?php if ($row1['fullname'] == "default") { ?>
                                            <option value=<?= $row1['id']; ?>.<?= $row1['fullname']; ?> selected><?= $row1['fullname']; ?> </option>
                                        <?php } else // Not selected
                                        { ?>

                                            <option value=<?= $row1['id']; ?>.<?= $row1['fullname']; ?>><?= $row1['fullname']; ?> </option>
                                        <?php } ?>
                                    <?php  } ?>
                                </select>

                                <h6 class="text text-gray-800">Procedure:</h6>
                                <select name="procedure" class="form-control" id="exampleFormControlSelect1" required>
                                    <option value="Checkup">Checkup </option>
                                    <option value="Filling">Filling </option>
                                    <option value="Whitening">Whitening </option>
                                    <option value="Extraction">Extraction </option>
                                </select>

                                <h5 class="text text-gray-800">Appointment time:</h5>
                                <input class="form-control form-control-borderless text-dark" id="actdeadline" type="text" name="appointment_time" placeholder="mm/dd/yy HH:MM:SS" value="<?php echo $appointment_time; ?>" data-input required>
                                
                                <div class="col-md-12 p-2">
                                    <button class="btn btn-lg btn-primary" type="submit" name="add_appointment_patient">Book Appointment</button>
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