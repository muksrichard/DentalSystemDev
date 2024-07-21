 <!-- Sidebar -->
 <?php

  //session_start();
  $username = $_SESSION['email'];
  $role = $_SESSION['role'];

  if (!isset($_SESSION['email']) || $role != "patient") {
    # code...
    header("location:../login.php");
    //session_start();
  }

  //include('includes/taction.php');
  // include('includes/config.php');


  ?>
 <div class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="refresh">
   <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

     <!-- Sidebar - Brand -->
     <a class="sidebar-brand d-flex align-items-center justify-content-center" href="sindex.php">
       <div class="sidebar-brand-icon ">

       </div>
       <?php
        //session_start();

        if (!isset($_SESSION['email']) || $role != "patient") {
          # code...
          header("location:login.php");
          //session_start();
        }

        ?>
       <div class="sidebar-brand-text mx-3">
         <h6><?= $_SESSION['role']; ?></h6>
       </div>
     </a>

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <!-- Nav Item - Dashboard
     <li class="nav-item active">
       <a class="nav-link" href="sindex.php">
         <i class="fas fa-chart-bar"></i>
         <span>Dashboard</span></a>
     </li> -->

     <hr class="sidebar-divider my-0">
     <!-- Divider -->
     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Tables -->
     <li class="nav-item">
       <a class="nav-link" href="add.php">
         <i class="fa fa-plus-circle fa-fw" aria-hidden="true"></i>
         <span>Book Appointment</span></a>
     </li>
     <!-- Divider -->
     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Dashboard -->
     <li class="nav-item ">
       <a class="nav-link" href="pappointments.php">
         <i class="fas fa-search"></i>
         <span>Appointments</span></a>
     </li>

     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Tables -->
     <li class="nav-item">
       <a class="nav-link" href="pconsultation.php">
         <i class="fa fa-globe fa-fw" aria-hidden="true"></i>
         <span>Consultation </span></a>
     </li>

     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Tables -->
     <li class="nav-item">
       <a class="nav-link" href="pinvoices.php">
         <i class="fa fa-database fa-fw" aria-hidden="true"></i>
         <span>Prescriptions </span></a>
     </li>
     <!-- Divider -->
     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Tables -->

     <hr class="sidebar-divider my-0">
     <!-- Nav Item - Tables -->
     <li class="nav-item">
       <a class="nav-link" href="pbilling.php">
         <i class="fa fa-database fa-fw" aria-hidden="true"></i>
         <span>Payments </span></a>
     </li>
  

     <!-- Divider -->
     <hr class="sidebar-divider my-0">

     <li class="nav-item">
       <a class="nav-link" href="logout.php">
         <i class="fa fa-list fa-fw" aria-hidden="true"></i>
         <span>Logout</span></a>
     </li>


   </ul>
   <!-- End of Sidebar -->

   <!-- Scroll to Top Button-->
   <a class="scroll-to-top rounded" href="#page-top">
     <i class="fas fa-angle-up"></i>
   </a>

   <!-- Logout Modal-->
   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
           <button class="close" type="button" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">×</span>
           </button>
         </div>
         <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
         <div class="modal-footer">
           <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
           <a class="btn btn-primary" href="logout.php">Logout</a>
         </div>
       </div>
     </div>
   </div>
 </div>