<?php
session_start();
$username = $_SESSION['email'];
$role = $_SESSION['role'];
$wid = $_SESSION['writerid'];
session_unset();
session_destroy();
header('location:login.php');
