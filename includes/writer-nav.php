 <!-- Sidebar -->
 <?php
  //session_start();

  // if (!isset($_SESSION['email']) || $_SESSION['role']!="writer")  {
  # code...
  // header("location:login.php");
  //}
  include('includes/taction.php');

  ?>

 <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

   <!-- Sidebar - Brand -->
   <a class="sidebar-brand d-flex align-items-center justify-content-center" href="windex.php">
     <div class="sidebar-brand-icon rotate-n-15">
       <i class="fas fa-laugh-wink"></i>
     </div>
     <div class="sidebar-brand-text mx-3">
       <h6><?= $_SESSION['role'] ?></h6><sup>2</sup>
     </div>
   </a>

   <!-- Divider -->
   <hr class="sidebar-divider my-0">

   <!-- Nav Item - Dashboard -->
   <li class="nav-item active">
     <a class="nav-link" href="windex.php">
       <i class="fas fa-chart-bar"></i>
       <span>Consultation</span></a>
   </li>

   <!-- Divider -->
   <hr class="sidebar-divider">
   <!-- Nav Item - Tables -->
   <li class="nav-item">
     <a class="nav-link" href="prescription.php">
       <i class="fas fa-plus"></i>
       <span>Prescription</span></a>
   </li>
   <!-- Divider -->
   <hr class="sidebar-divider my-0">

   <!-- Nav Item - Tables -->
   <li class="nav-item">
     <a class="nav-link" href="completed.php">
       <i class="fa fa-spinner fa-fw" aria-hidden="true"></i>
       <span>Completed</span></a>
   </li>
   <!-- Divider -->
   <hr class="sidebar-divider my-0">
   <!-- Nav Item - Tables -->
   <li class="nav-item">
     <a class="nav-link" href="logout.php">
       <i class="fa fa-list fa-fw" aria-hidden="true"></i>
       <span>Logout </span></a>
   </li>


   <!-- Divider -->
   <hr class="sidebar-divider my-0">





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
         <a class="btn btn-primary" href="login.php">Logout</a>
       </div>
     </div>
   </div>
 </div>