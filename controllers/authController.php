<?php
// require 'vendor/autoload.php';
require_once 'emailController.php';
require_once 'config/db.php';

use PHPMailer\PHPMailer\PHPMailer;

$first_name         = "";
$last_name          = "";
$email              = "";
$phonenumber        = "";
$password           = "";
$confirm_password   = "";
$errors = [];

// check if username has emoji
function isStringHasEmojis($string)
{
    $emojis_regex =
        '/[\x{0080}-\x{02AF}'
        . '\x{0300}-\x{03FF}'
        . '\x{0600}-\x{06FF}'
        . '\x{0C00}-\x{0C7F}'
        . '\x{1DC0}-\x{1DFF}'
        . '\x{1E00}-\x{1EFF}'
        . '\x{2000}-\x{209F}'
        . '\x{20D0}-\x{214F}'
        . '\x{2190}-\x{23FF}'
        . '\x{2460}-\x{25FF}'
        . '\x{2600}-\x{27EF}'
        . '\x{2900}-\x{29FF}'
        . '\x{2B00}-\x{2BFF}'
        . '\x{2C60}-\x{2C7F}'
        . '\x{2E00}-\x{2E7F}'
        . '\x{3000}-\x{303F}'
        . '\x{A490}-\x{A4CF}'
        . '\x{E000}-\x{F8FF}'
        . '\x{FE00}-\x{FE0F}'
        . '\x{FE30}-\x{FE4F}'
        . '\x{1F000}-\x{1F02F}'
        . '\x{1F0A0}-\x{1F0FF}'
        . '\x{1F100}-\x{1F64F}'
        . '\x{1F680}-\x{1F6FF}'
        . '\x{1F910}-\x{1F96B}'
        . '\x{1F980}-\x{1F9E0}]/u';
    preg_match($emojis_regex, $string, $matches);
    return !empty($matches);
}


// escape string
function escape($string)
{
    global $conn;
    return mysqli_real_escape_string($conn, $string);
}

//remove html entities from post request
function clean($string)
{
    return htmlentities($string);
}

//remove html entities from username solo,retain emoji request
function cleanusername($string)
{
    return htmlentities($string, ENT_HTML5);
}
//redirect funtion
function redirect($location)
{
    return header("Location: {$location}");
}

function set_message($message)
{


    if (!empty($message)) {


        $_SESSION['message'] = $message;
    } else {

        $message = "";
    }
}

function display_message()
{

    if (isset($_SESSION['message'])) {


        echo $_SESSION['message'];

        unset($_SESSION['message']);
    }
}

function validation_errors($error_message)
{
    //     $error_message = <<<DELIMITER

    // <div class="alert alert-danger bg-danger border-0 alert-dismissible text-center fade show">
    //   	<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    //   	$error_message
    //  </div>
    // DELIMITER;
    $error_message = <<<DELIMITER

									<div class="font-20 text-center text-dark">$error_message</div>

DELIMITER;
    return $error_message;
}



function row_count($result)
{
    return mysqli_num_rows($result);
}

function email_exists($email)
{
    global $conn;
    $sql = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (row_count($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function username_exists($username)
{
    global $conn;
    $sql = "SELECT id FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}

function phonenumber_exists($phonenumber)
{
    global $conn;
    $phonenumberke = preg_replace("/^0/", "254", $phonenumber);
    $phonenumberke = preg_replace("/^7/", "2547", $phonenumber);
    $sql = "SELECT id FROM users WHERE phonenumber = '$phonenumberke' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (row_count($result) == 1) {
        return true;
    } else {
        return false;
    }
}


function confirm($result)
{
    global $conn;

    if (!$result) {

        die("QUERY FAILED" . mysqli_error($conn));
    }
}
// run query
function query($query)
{

    global $conn;
    $result =  mysqli_query($conn, $query);
    confirm($result);
    return $result;
}

// fetch asociative array
function fetch_array($result)
{
    global $conn;
    return mysqli_fetch_array($result);
}

//token generator for reset password link
function token_generator()
{


    $token = $_SESSION['token'] =  md5(uniqid(mt_rand(), true));

    return $token;
}


/****************Recover Password function ********************/

function recover_password()
{

    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {

            $email = clean($_POST['email']);


            if (email_exists($email)) {


                $resetpass_code = md5($email . microtime());


                setcookie('temp_access_code', $resetpass_code, time() + (60 * 60 * 24));


                $sql = "UPDATE users SET resetpass_code = '" . escape($resetpass_code) . "' WHERE email = '" . escape($email) . "'";
                $result = query($sql);

                //get username of last insert id 
                $sqldetail = "SELECT username,first_name FROM users WHERE email=?";
                $stmtdetail = $conn->prepare($sqldetail);
                $stmtdetail->bind_param("s", $email);
                $stmtdetail->execute();
                $resultD = $stmtdetail->get_result();
                $rowD = $resultD->fetch_assoc();
                $username = $rowD['username'];
                $first_name = $rowD['first_name'];


                //send password reset email 
                $mailusername = $username;
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->SMTPDebug = 0;
                $mail->Host = Constants::SMTP_HOST;
                // $mail->Host = Constants::SMTP_HOST;
                $mail->Port = 587;
                $mail->SMTPAuth = true;
                $mail->Username = Constants::SMTP_USER;
                // $mail->Username = Constants::SMTP_USER;
                // $mail->Password = Constants::SMTP_PASSWORD;
                $mail->Password = Constants::SMTP_PASSWORD;
                $mail->setFrom('resets@dentalagencies.com', 'Dental');
                $mail->addReplyTo('noreply@dentalagencies.com', 'Dental');
                $mail->addAddress($email, $first_name);
                $mail->Subject = 'Password Reset Link';
                $template = file_get_contents('reset.html');
                $searcharray  = array("{{username}}", "{{email}}", "{{resetpass_code}}");
                $replacearray = array($mailusername, $email, $resetpass_code);
                $newtemplate = str_replace($searcharray, $replacearray, $template);
                $mail->msgHTML($newtemplate, __DIR__);
                if (!$mail->send()) {
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'The email message was sent.';
                }




                // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
                // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                // Check your email inbox or spam folder for a password reset link
                // </div>');


                set_message('<div class="form-label">
                            Check your email inbox or spam folder for a password reset link
                            </div></div>');

                redirect("login.php");
            } else {


                echo validation_errors("The email provided does not exist.");
            }
        } else {


            redirect("login.php");
        }




        // token checks



    } // post request





}

/**************** Code  Validation ********************/


function validate_code()
{


    if (isset($_COOKIE['temp_access_code'])) {

        if (!isset($_GET['email']) && !isset($_GET['code'])) {

            redirect("login.php");
        } else if (empty($_GET['email']) || empty($_GET['code'])) {

            redirect("login.php");
        } else {



            if (isset($_POST['code'])) {

                $email = clean($_GET['email']);

                $resetpass_code = clean($_POST['code']);

                $sql = "SELECT id FROM users WHERE resetpass_code = '" . escape($resetpass_code) . "' AND email = '" . escape($email) . "'";
                $result = query($sql);

                if (row_count($result) == 1) {

                    setcookie('temp_access_code', $resetpass_code, time() + (60 * 60 * 24));

                    redirect("reset.php?email=$email&code=$resetpass_code");
                } else {



                    echo validation_errors("Password reset code has expired ,request another <a href='forgotpassword.php'>here</a>");
                }
            }
        }
    } else {



        set_message('<div class="form-label">
                                Sorry your validation cookie has expired. Please request a new reset password email
                     </div>
                            ');




        redirect("forgotpassword.php");
    }
}

/**************** Password Reset Function ********************/


function password_reset()
{

    if (isset($_COOKIE['temp_access_code'])) {


        if (isset($_GET['email']) && isset($_GET['code'])) {



            if (isset($_SESSION['token']) && isset($_POST['token'])) {


                if ($_POST['token'] === $_SESSION['token']) {


                    if ($_POST['password'] === $_POST['confirm_password']) {


                        $updated_password = $_POST['password'];


                        $sql = "UPDATE users SET password = '" . escape($updated_password) . "', resetpass_code = 0, active=1 WHERE email = '" . escape($_GET['email']) . "'";
                        query($sql);


                        // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
                        // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        // You password has been updated,  login to continue.
                        // </div>');

                        set_message('
                            <div class="form-label">
                                You password has been updated,  login to continue.
                            </div>
                            ');


                        redirect("login.php");
                    } else {

                        echo validation_errors("Passwords don't match");
                    }
                }
            }
        }
    } else {

        // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
        // 				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        // 				Sorry your time has expired ,request a new passowrd reset email
        // 		   </div>');

        set_message('<div class="form-label">
						Sorry your time has expired ,request a new passowrd reset email
                            </div>
                            ');

        redirect("forgotpassword.php");
    }
}


// register user function 
function register_user($first_name, $email, $dateofbirth, $phonenumber, $password)
{
    global $conn;
    $dateofbirth = $dateofbirth;
    $first_name  = escape($first_name);
    $email       = $email;
    $phonenumber = escape($phonenumber);
    $password    = escape($password);
    $hashedpassword    = md5($password);
    $validation_code = 1;
    $user_type = "patient";
    $phonenumberke = preg_replace("/^0/", "254", $phonenumber);
    $phonenumberke = preg_replace("/^7/", "2547", $phonenumber);
    $createdat =  date("Y-m-d H:i:s");

    $sql = "INSERT INTO users(fullname, email, dateofbirth, phonenum, password,hashedpass, validation_code,user_type,created)";
    $sql .= " VALUES('$first_name','$email','$dateofbirth','$phonenumberke','$password','$hashedpassword','$validation_code', '$user_type','$createdat')";

    // query('SET NAMES utf8mb4');

    $mailtime =  date("Y-m-d H:i:s");
    $result = query($sql);
    confirm($result);
    $userid = $conn->insert_id;

    // //new mail 
    // $mailusername = $email;
    // $mail = new PHPMailer;
    // $mail->isSMTP();
    // $mail->SMTPDebug = 0;
    // $mail->Host = Constants::SMTP_HOST;
    // $mail->Port = 587;
    // $mail->SMTPAuth = true;
    // $mail->Username = Constants::SMTP_USER;
    // $mail->Password = Constants::SMTP_PASSWORD;
    // $mail->setFrom('registration@dentalagencies.com', 'Dental');
    // $mail->addReplyTo('noreply@dentalagencies.com', 'Dental');
    // $mail->addAddress($email, $first_name);
    // $mail->Subject = 'Welcome to Dental';
    // $template = file_get_contents('message.html');
    // $searcharray  = array("{{username}}", "{{email}}", "{{validation_code}}");
    // $replacearray = array($mailusername, $email, $validation_code);
    // $newtemplate = str_replace($searcharray, $replacearray, $template);
    // $mail->msgHTML($newtemplate, __DIR__);
    // if (!$mail->send()) {
    //     //echo 'Mailer Error: ' . $mail->ErrorInfo;
    //     $query = "INSERT INTO emails(mail,username,subject,sendtime,company,error_info)VALUES(?,?,?,?,?,?)";
    //     $stmt = $conn->prepare($query);
    //     $stmt->bind_param("ssssss", $email, $mailusername, $mail->Subject, $mailtime, $mail->Host, $mail->ErrorInfo);
    //     $stmt->execute();
    // } else {
    //     //echo 'The email message was sent.';
    //     $ErrorInfo = "NULL";
    //     $query = "INSERT INTO emails(mail,username,subject,sendtime,company,error_info)VALUES(?,?,?,?,?,?)";
    //     $stmt = $conn->prepare($query);
    //     $stmt->bind_param("ssssss", $email, $mailusername, $mail->Subject, $mailtime, $mail->Host, $ErrorInfo);
    //     $stmt->execute();
    // }


    // $userid = $conn->insert_id;

    //get email of last insert id 
    $sql = "SELECT email FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $email = $row['email'];

    //set session and automatically login user and redirect to 
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $user_type;
    $_SESSION['userid'] = $userid;
    // $_SESSION['email'] = $email;
    return true;
}


/****************Activate user using email functions ********************/


function activate_user()
{
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "GET") {


        if (isset($_GET['email'])) {


            $email = clean($_GET['email']);
            //var_dump($email);
            $validation_code = clean($_GET['code']);


            // $sql = "SELECT id,validation_code FROM users WHERE email = '" . $_GET['email'] . "' AND validation_code = '" . escape($_GET['code']) . "' ";
            // $result = query($sql);
            // confirm($result);

            $sql = "SELECT id,validation_code FROM users WHERE email=? AND validation_code =?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $validation_code);
            $stmt->execute();
            $result = $stmt->get_result();


            //$row =fetch_array($result);
            //while 
            //var_dump($row);
            if (row_count($result) == 1) {
                $sql2 = "UPDATE users SET active = 1, validation_code = 0 WHERE email = '" . $email . "' AND validation_code = '" . escape($validation_code) . "' ";
                $result2 = query($sql2);
                confirm($result2);

                //get username of last insert id 
                $sql = "SELECT email,user_type,id FROM users WHERE email=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $username = $row['email'];
                $role = $row['user_type'];
                $userid = $row['id'];

                //set session and automatically login user and redirect to 
                $_SESSION['email'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['userid'] = $userid;


                //$_SESSION['email'] = $email;
                //$_SESSION['message'] = "Email verified ,proceed to activate account";

                // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
                // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                // Email verified. Purchase a Package to get access to more features.
                // </div>');

                set_message('<div class="alert alert-info border-0 bg-info alert-dismissible fade show">
                <div class="text-white">
				Email verified. Purchase a Package to get access to more features.
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>');

                redirect("dashboard.php");
            } else {

                // set_message('<div class="alert alert-warning bg-warning border-0 alert-dismissible text-center fade show">
                //     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                //     Email already confirmed. Login below
                // </div>');

                set_message('<div class="alert alert-info border-0 bg-info alert-dismissible fade show">
                <div class="text-white">
                    Email already confirmed. Login below
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>');

                redirect("login.php");
            }
        }
    }
} // function 

//header('Cache-Control: max-age=900');

function validateEmail($email)
{
    // SET INITIAL RETURN VARIABLES

    $emailIsValid = FALSE;

    // MAKE SURE AN EMPTY STRING WASN'T PASSED

    if (!empty($email)) {
        // GET EMAIL PARTS

        $domain = ltrim(stristr($email, '@'), '@') . '.';
        $user   = stristr($email, '@', TRUE);

        // VALIDATE EMAIL ADDRESS

        if (
            !empty($user) &&
            !empty($domain) &&
            checkdnsrr($domain)
        ) {
            $emailIsValid = TRUE;
        }
    }

    // RETURN RESULT

    return $emailIsValid;
};
// SIGN UP USER
if (isset($_POST['signup-btn'])) {

    $searcharray  = array("+", " ");
    $replacearray = array(" ", "");
    $searchunamearray  = array(" ");
    $replaceunamrearray = array("_");
    $first_name             = $_POST['first_name'];
    if (isStringHasEmojis($first_name)) {
        $errors[] = "Name cannot contain emojis";
    };
    $first_name             = clean($_POST['first_name']);
    // $username               = strtolower($_POST['username']);

    // if (isStringHasEmojis($username)) {
    //     $errors[] = "Username cannot contain emojis";
    // };
    // $username               = clean(strtolower($_POST['username']));

    $email                  = clean(strtolower($_POST['email']));
    $dateofbirth            = clean($_POST['dateofbirth']);
    $phonenumber            = clean($_POST['phonenumber']);
    $phonenumber            = str_replace($searcharray, $replacearray, $phonenumber);
    $phonenumber            = trim($phonenumber, " \n\r\t\v\0#");
    $password               = clean($_POST['password']); //save password in plain text
    $confirm_password       = clean($_POST['confirm_password']);

    $min = 3;
    $max = 100;
    $maxmail = 100;
    $phone = 15;

    if (empty($first_name)) {
        $errors['first_name'] = 'First name cannot be empty';
    }
    if (strlen($first_name) < $min) {

        $errors[] = "Your first name cannot be less than {$min} characters";
    }
    if (strlen($first_name) > $max) {
        $errors[] = "Your first name cannot be more than {$max} characters";
    }
    // if (empty($username)) {
    //     $errors[] = "Your username cannot be empty";
    // }
    // if (strlen($username) < $min) {
    //     $errors[] = "Your Username cannot be less than {$min} characters";
    // }
    // if (strlen($username) > $max) {
    //     $errors[] = "Your Username cannot be more than {$max} characters";
    // }
    // if (preg_match('/\s/', $username)) {
    //     $errors[] = "Your Username cannot be contain spaces.Please Correct it and try again";
    // }
    // $username               = str_replace($searchunamearray, $replaceunamrearray, $username);
    // $username               = trim($username, " \n\r\t\v\0#");

    // Check if username already exists
    // if (username_exists($username)) {
    //     $errors['username'] = "Username already exists";
    // }

    // if ($referrer=="none") {
    //     $errors['inviter'] = 'Inviter cannot be empty';
    // }

    if (empty($email)) {
        $errors['email'] = 'Email required';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email address";
    }
    if (strlen($email) > $maxmail) {
        $errors[] = "Your email cannot be more than {$maxmail} characters";
    }

    if (validateEmail($email) == FALSE) {
        $errors['email'] = "Invalid email address.Check your email and try again";
    }

    // // Check if email already exists
    if (email_exists($email)) {
        $errors['email'] = "Email already exists";
    }
    if (empty($phonenumber)) {

        $errors[] = "Your phone number cannot be empty";
    }

    if (phonenumber_exists($phonenumber)) {

        $errors[] = "Sorry that phone number already exists in our system";
    }

    if (strlen($phonenumber) > $phone) {

        $errors[] = "Your phone number  cannot be more than {$phone} numbers";
    }

    // if (strlen($referrer) > $max) {

    //     $errors[] = "Your referrer  cannot be more than {$max} characters";
    // }

    if (empty($password)) {
        $errors['password'] = 'Password required';
    }
    if (isset($password) && $password !== $confirm_password) {
        $errors['confirm_password'] = 'The two passwords do not match';
    }




    if (count($errors) === 0) {
        /* //get referrer id and package
        $sql = "SELECT id,package FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $referrer);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $refid =$row['id'];
        $package = $row['package'];
        if ($package==0) {
            $investamount= 0;
        }
        if ($package==1) {
            $investamount= 700;
        }if ($package==2) {
            $investamount= 1100;
        }if ($package==3) {
            $investamount= 3000;
        }

        if ($package!==0) {
            $sql = "INSERT INTO referrals(uid, package, amountEarned)";
            $sql .= " VALUES('$refid','$package','$investamount')";
            query('SET NAMES utf8mb4');

            $result = query($sql);
            confirm($result);
        } */




        // register  user in db
        if (register_user($first_name, $email, $dateofbirth, $phonenumber, $password)) {



            // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
            // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            // Account created successfully. Purchase a Package to get access to more features.
            // </div>');
            // redirect("dashboard.php");
            // set_message('<div class="alert alert-success bg-success border-0 alert-dismissible text-center fade show">
            // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            // Account created successfully. Check email inbox or spam folder for activation email.
            // </div>');

            set_message('<div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                            <div class="text-white">
                            Account created successfully. Login below.
                            Or click
                            </div>');

            redirect("login.php");
        } else {


            // set_message('<div class="alert alert-danger bg-danger border-0 alert-dismissible text-center fade show">
            // <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            // Sorry we could not register the user
            // </div>');
            set_message('<div class="alert alert-info border-0 bg-info alert-dismissible fade show">
                            <div class="text-white">
                                Sorry we could not register the user
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>');

            redirect("register.php");
        }



        //old register start
        /* $query = "INSERT INTO users SET username=?, email=?, password=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $username, $email, $password);
        $result = $stmt->execute();

        if ($result) {
            $user_id = $stmt->insert_id;
            $stmt->close();

            //sendVerificationEmail($email, $token);

            $_SESSION['email']ername;
            $_SESSION['email'] = $email;
            $_SESSION['verified'] = false;
            $_SESSION['message'] = 'You are logged in!';
            $_SESSION['type'] = 'alert-success';
            redirect('index.php');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
        } */ //old register end
    } else {
        foreach ($errors as $error) {

            echo validation_errors($error);
        }
    }
}

/* // LOGIN user using email or username 
if (isset($_POST['login-btn'])) {
    if (empty($_POST['username'])) {
        $errors['username'] = 'Username or email required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (count($errors) === 0) {
        $query = "SELECT * FROM users WHERE username=? OR email=? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $username, $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) { // if password matches
                $stmt->close();

                $_SESSION['email'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['verified'] = $user['verified'];
                $_SESSION['message'] = 'You are logged in!';
                $_SESSION['type'] = 'alert-success';
                header('location: index.php');
                exit(0);
            } else { // if password does not match
                $errors['login_fail'] = "Wrong username / password";
            }
        } else {
            $_SESSION['message'] = "Database error. Login failed!";
            $_SESSION['type'] = "alert-danger";
        }
    }
} */

//multiauth login
$msg = [];

if (isset($_POST['login-submit'])) {

    $email = strtolower($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT id,email,password,user_type FROM users WHERE email=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $resultRowCount = $result->num_rows;
    if ($resultRowCount > 0) {
        
        if ($row['user_type'] == "admin" || $row['user_type'] == "reception" || $row['user_type'] == "dentist" || $row['user_type'] == "patient") {
            session_regenerate_id();
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['user_type'];
            $_SESSION['userid'] = $row['id'];
            $_SESSION['writerid'] = $row['id'];
            $_SESSION['status'] = 0;
            session_write_close();
            //$msg[] = "Incorrect email or password";
        }
    }


    if ($result->num_rows == 1 && $_SESSION['role'] == "reception" && $_SESSION['status'] == 0) {
        header("location:add.php");
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "dentist" && $_SESSION['status'] == 0) {
        header("location:windex.php");
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "admin" && $_SESSION['status'] == 0) {
        header("location:admin.php");
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "patient" && $_SESSION['status'] == 0) {
        header("location:patient.php");
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "reception" && $_SESSION['status'] == 1) {
        $msg[] = "Account disabled Contact administrator";
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "writer" && $_SESSION['status'] == 1) {
        $msg[] = "Account disabled Contact administrator";
    } else if ($result->num_rows == 1 && $_SESSION['role'] == "admin" && $_SESSION['status'] == 1) {
        $msg[] = "Account disabled Contact administrator";
    } else {
        $msg[] = "Incorrect username or password";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['email']);
    unset($_SESSION['email']);
    unset($_SESSION['verify']);
    unset($_SESSION['cart']);
    unset($_SESSION['qty_array']);
    header("location: login.php");
    exit(0);
}
