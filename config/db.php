<?php
require_once 'constants.php';
$conn = new mysqli(Constants::DB_HOST, Constants::DB_USERNAME, Constants::DB_PASWORD, Constants::DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sqltimezone ="SET SESSION time_zone = '+3:00'";
$stmttimezone = $conn->prepare($sqltimezone);
$stmttimezone->execute();

$sqlcharset = "SET NAMES utf8mb4";
$stmtcharset = $conn->prepare($sqlcharset);
$stmtcharset->execute();