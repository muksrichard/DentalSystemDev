<?php
include 'config.php';

$patient_name = "";
$fullname = "";
$dob = "";
$dateofbirth = "";
$procedure = "";
$phonenumber = "";
$address = "";
$appointment_time = "";
$errors = [];

if (isset($_POST['add_appointment'])) {


	$inserted_by_id = htmlspecialchars(strip_tags($_POST['inserted_by_id']));
	$patient_id = (int) filter_var($_POST['patient_id'], FILTER_SANITIZE_NUMBER_INT);
	$procedure = htmlspecialchars(strip_tags($_POST['procedure']));
	$appointment_time = htmlspecialchars(strip_tags($_POST['appointment_time']));
	$dates = date("Y-m-d H:i:s");

	if (count($errors) === 0) {

		$query = "INSERT INTO appointment(user_id,patient_id,activity,appointmentdate,Dtime)VALUES(?,?,?,?,?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("sssss", $inserted_by_id, $patient_id, $procedure, $appointment_time, $dates);

		if ($stmt->execute()) {

			echo '<div class="alert alert-success alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b> Successfully added appointment.Go to Appoinments page for more details </b>
					</div>';

			// $inserted_by_id = "";
			$patient_id = "";
			$procedure = "";
			$phonenumber = "";
			$appointment_time = "";
			$dates = "";
		} else {

			echo '<div class="alert alert-danger alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b>Insert failed.Please try again unique one </b>
					</div>';
		}
	} else {
		foreach ($errors as $error) {

			echo ($error);
		}
	}
}
if (isset($_POST['add_appointment_patient'])) {


	$inserted_by_id = htmlspecialchars(strip_tags($_POST['inserted_by_id']));
	$patient_id = htmlspecialchars(strip_tags($_POST['inserted_by_id']));
	$dentist_id = (int) filter_var($_POST['dentist_id'], FILTER_SANITIZE_NUMBER_INT);
	$procedure = htmlspecialchars(strip_tags($_POST['procedure']));
	$appointment_time = htmlspecialchars(strip_tags($_POST['appointment_time']));
	$dates = date("Y-m-d H:i:s");

	//check if same appointment start date exists
	$sql0 = "SELECT appointmentdate FROM appointment WHERE appointmentdate='$appointment_time'";
	$stmt0 = $conn->prepare($sql0);
	$stmt0->execute();
	$result0 = $stmt0->get_result();
	$rowCountAppointment = $result0->num_rows;
	if ($rowCountAppointment>0) {
		$errors[] ='Appointment time not available try choosing 3 hrs later';
	}
	// while ($row0 = $result0->fetch_assoc()) {
	// 	$appointment_date = $row0['appointmentdate'];
	// }

	if (count($errors) === 0) {

		$query = "INSERT INTO appointment(user_id,patient_id,activity,appointmentdate,dentist_id,Dtime)VALUES(?,?,?,?,?,?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssssss", $inserted_by_id, $patient_id, $procedure, $appointment_time, $dentist_id, $dates);

		if ($stmt->execute()) {

			echo '<div class="alert alert-success alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b> Successfully added appointment.Go to Appointments page to make payment </b>
					</div>';

			$dentist_id = "";
			$patient_id = "";
			$procedure = "";
			$phonenumber = "";
			$appointment_time = "";
			$dates = "";
		} else {

			echo '<div class="alert alert-danger alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b>Insert failed.Please try again unique one </b>
					</div>';
		}
	} else {
		foreach ($errors as $error) {

			echo ($error);
		}
	}
}

if (isset($_POST['add_patient'])) {


	$patient_name = htmlspecialchars(strip_tags($_POST['patient_name']));
	$dob = htmlspecialchars(strip_tags($_POST['dob']));
	$phonenumber = htmlspecialchars(strip_tags($_POST['phonenumber']));
	$address = htmlspecialchars(strip_tags($_POST['address']));
	$inserted_by = htmlspecialchars(strip_tags($_POST['inserted_by']));
	$dates = date("Y-m-d H:i:s");

	if (count($errors) === 0) {

		$query = "INSERT INTO patients(name,dateofbirth,phonenumber,address,inserted_by,Dtime)VALUES(?,?,?,?,?,?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssssss", $patient_name, $dob, $phonenumber, $address, $inserted_by, $dates);

		if ($stmt->execute()) {

			echo '<div class="alert alert-success alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b> Successfully inserted.Go to schedules page for more details </b>
					</div>';

			$patient_name = "";
			$dob = "";
			$phonenumber = "";
			$address = "";
			$inserted_by = "";
			$dates = "";
		} else {

			echo '<div class="alert alert-danger alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b>Insert failed.Please try again </b>
					</div>';
		}
	} else {
		foreach ($errors as $error) {

			echo ($error);
		}
	}
}
if (isset($_POST['add_user'])) {


	$fullname = htmlspecialchars(strip_tags($_POST['fullname']));
	$dateofbirth = htmlspecialchars(strip_tags($_POST['dateofbirth']));
	$phonenumber = htmlspecialchars(strip_tags($_POST['phonenumber']));
	$email = htmlspecialchars(strip_tags($_POST['email']));
	$password = htmlspecialchars(strip_tags($_POST['password']));
	$user_type = htmlspecialchars(strip_tags($_POST['user_type']));
	$inserted_by = htmlspecialchars(strip_tags($_POST['inserted_by']));
	$created = date("Y-m-d H:i:s");
	$validation_code = 1;

	if (count($errors) === 0) {

		$query = "INSERT INTO users(fullname,dateofbirth,phonenum,email,password,user_type,created,validation_code)VALUES(?,?,?,?,?,?,?,?)";
		$stmt = $conn->prepare($query);
		$stmt->bind_param("ssssssss", $fullname, $dateofbirth, $phonenumber, $email, $password, $user_type, $created, $validation_code);

		if ($stmt->execute()) {

			echo '<div class="alert alert-success alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b> Successfully inserted.Go to users page for more details </b>
					</div>';

			$fullname = "";
			$dateofbirth = "";
			$phonenumber = "";
			$email = "";
			$password = "";
			$user_type = "";
			$created = "";
			$validation_code = "";
		} else {

			echo '<div class="alert alert-danger alert-dismissible text-center">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<b>Insert failed.Please try again </b>
					</div>';
		}
	} else {
		foreach ($errors as $error) {

			echo ($error);
		}
	}
}

//code for display details  button
if (isset($_GET['details'])) {
	$id = $_GET['details'];
	$query = "SELECT appointment.id,users.id AS user_id,users.fullname AS patient_id,
	appointment.activity, appointment.appointmentdate FROM appointment INNER JOIN users
	ON users.id=appointment.user_id
	WHERE appointment.send=1 AND appointment.id=?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();


	$patient_id =  $row['patient_id'];
	$activity = $row['activity'];
	// $orderno = $row['orderno'];
	// $ordertitle = $row['ordertitle'];
	// $juserame = $row['juserame'];
	// $pages = $row['pages'];
	// $actcost = $row['actcost'];
	// $wrcost = $row['wrcost'];
	// $actdeadline = $row['actdeadline'];
	// $wrdeadline = $row['wrdeadline'];
	// $instructions = $row['instructions'];
	//$attachs=$_FILES['attachs']['name'];
	//$upload="../uploads/".$attachs;

}

//code for display details  button
if (isset($_GET['cdetails'])) {
	$id = $_GET['cdetails'];
	$query = "SELECT consultation.id,users.email AS user_id,users.fullname AS patient_id,
    consultation.constltation_date, consultation.treatment,consultation.diagnosis 
    FROM consultation 
    INNER JOIN users ON users.id=consultation.patient_id WHERE consultation.consult=1 AND consultation.id=? 
    ORDER BY consultation.constltation_date DESC";
	$stmt = $conn->prepare($query);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_assoc();


	$patient_id =  $row['patient_id'];
	$treatment = $row['treatment'];
	// $orderno = $row['orderno'];
	// $ordertitle = $row['ordertitle'];
	// $juserame = $row['juserame'];
	// $pages = $row['pages'];
	// $actcost = $row['actcost'];
	// $wrcost = $row['wrcost'];
	// $actdeadline = $row['actdeadline'];
	// $wrdeadline = $row['wrdeadline'];
	// $instructions = $row['instructions'];
	//$attachs=$_FILES['attachs']['name'];
	//$upload="../uploads/".$attachs;

}
