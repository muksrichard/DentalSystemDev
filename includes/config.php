<?php
include_once('constants.php');
$conn = new mysqli(Constants::DEV_URL, Constants::DB_USERNAME, Constants::DB_PASWORD, Constants::DB_NAME);

//set mode 
$querySM = "SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));";
$stmtSM = $conn->prepare($querySM);
$stmtSM->execute();

if ($conn->connect_error) {
	die("Could not connect to database!" . $conn->connect_error);
}
