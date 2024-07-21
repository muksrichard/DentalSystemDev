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

$sql0 = "SELECT email FROM users WHERE email='$cs'";
$stmt0 = $conn->prepare($sql0);
$stmt0->execute();
$result0 = $stmt0->get_result();
while ($row0 = $result0->fetch_assoc()) {
    $inserted_by = $row0['email'];
    //echo $row0['id']; 
}

// Loading users with support role
$sql1 = "SELECT id,email FROM users WHERE user_type='support'";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$result1 = $stmt1->get_result();

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
                <h3 class="h3 mb-0 text-white-800"> Prescriptions </h3>
            </div>

            <!-- Topbar Navbar -->

        </nav>

        <div class="container">

            <h3 class="text-center text-info">Records</h3>
            <br>
            <?php
            $query = "SELECT prescription.id,users.fullname,prescription.prescript_name, prescription.prescript_qty,prescription.prescript_dosage FROM prescription INNER JOIN consultation
            ON prescription.consultation_id= consultation.id INNER JOIN users on users.id=consultation.patient_id";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result()
            ?>

            <div class="row justify-content-center">

                <div class="card col-12 col-md-12 col-lg-12">
                    <?php include('includes/taction.php'); ?>


                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Records</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive container">
                            <table class="table table-bordered table-stripped table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Patient name </th>
                                        <th>Drug name</th>
                                        <th>Quantity</th>
                                        <th>Dosage</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Patient name </th>
                                        <th>Drug name</th>
                                        <th>Quantity</th>
                                        <th>Dosage</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <td><?= $row['fullname']; ?> </td>
                                            <td><?= $row['prescript_name']; ?></td>
                                            <td> <?= $row['prescript_qty']; ?> </td>

                                            <td><?= $row['prescript_dosage'];  ?> </td>
                                            <td>
                                                <a href="includes/action.php?view=<?= $row['id']; ?>&page=appointments" class="badge badge-secondary p-2">Bill</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>


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
                <span>Copyright &copy; Dental System 2023</span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->
</body>

</html>