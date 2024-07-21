<?php
@ob_start();

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

$sql0 = "SELECT id,email,phonenum FROM users WHERE email='$cs'";
$stmt0 = $conn->prepare($sql0);
$stmt0->execute();
$result0 = $stmt0->get_result();
while ($row0 = $result0->fetch_assoc()) {
    $inserted_by = $row0['email'];
    $inserted_by_id = $row0['id'];
    $phonenumber = $row0['phonenum'];
    //echo $row0['id']; 
}
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
                <h3> <a class="h3 mb-0 text-white" href="profile.php?profile=<?= $userid;
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

                <div class="col-12 col-md-10 col-lg-8">


                    <form method="post" action="depologic.php" enctype="multipart/form-data" class="card card-sm">

                        <input type="hidden" name="inserted_by_id" value=<?= $inserted_by_id; ?>>

                        <div class="col">

                            <div class="card-body row no-gutters align-items-center">
                                <h6 class="col-md-12 p-1">Wait for Mpesa popup : <?= date("jS M  g:i a"); ?></h6>


                                <?php
                                //header("refresh:30;url=verify.php");
                                # access token
                                $consumerKey = 'QB99OuMTWlce5FjYsODuguqowxDA1c5I'; //Fill with your app Consumer Key
                                $consumerSecret = 'hRjknGmyjEhWuTmy'; // Fill with your app Secret

                                # define the variales
                                # provide the following details, this part is found on your test credentials on the developer account
                                $BusinessShortCode = '4107535';
                                // $BusinessShortCode = '7837916'; also error
                                # Get the timestamp, format YYYYmmddhms -> 20181004151020
                                $Timestamp = date('YmdHis');
                                $Passkey = 'ac5873e9b414d28b0c66006b067183b6426c7829f32fdacd7f27638016d51fd1';

                                # Get the base64 encoded string -> $password. The passkey is the M-PESA Public Key
                                $Password = base64_encode($BusinessShortCode . $Passkey . $Timestamp);

                                /*
                                            This are your info, for
                                            $PartyA -  ACTUAL clients phone number or your phone number, format 2547********
                                            $AccountRefference - maybe invoice number, account number etc on production systems,
                                            TransactionDesc can be anything, probably a better description of or the transaction
                                            $Amount - total invoiced amount
                                            */


                                $PartyA = preg_replace("/^0/", "254", $phonenumber);
                                // $PartyA = '254707393712';
                                // $AccountReference = $csd;
                                $AccountReference = $phonenumber;
                                $TransactionDesc = 'activate account';
                                if (isset($_POST['autodepo'])) {

                                    $Amount = clean($_POST['amount']);
                                    $Amount = escape($Amount);
                                }
                                $callbackUrl = 'https://www.ralphagencies.com/callback_url.php';
                                // $callbackUrl = 'https://www.techarasolutions.com/daraja-tutorial/callback_url.php';
                                // $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
                                $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

                                # header for access token
                                $headers = ['Content-Type:application/json; charset=utf8'];

                                # M-PESA endpoint urls
                                // $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
                                $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
                                // $access_token_url = 'https://api.safaricom.co.ke/oauth/v1/generate';
                                //https://discord.com/invite/fxJyfGd
                                $curl = curl_init($access_token_url);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //disable ssl certificate verification
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                                curl_setopt($curl, CURLOPT_HEADER, FALSE);
                                curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
                                $result = curl_exec($curl);
                                $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $result = json_decode($result);
                                $access_token = $result->access_token;
                                curl_close($curl);

                                # header for stk push
                                $stkheader = ['Content-Type:application/json', 'Authorization:Bearer ' . $access_token];
                                $curl = curl_init();
                                // Check if initialization had gone wrong*    
                                if ($curl === false) {
                                    echo ('failed to initialize');
                                }
                                curl_setopt($curl, CURLOPT_URL, $url);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //disable ssl certificate verification
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader); //setting custom header

                                $curl_post_data = array(
                                    //Fill in the request parameters with valid values
                                    'BusinessShortCode' => $BusinessShortCode,
                                    'Password' => $Password,
                                    'Timestamp' => $Timestamp,
                                    'TransactionType' => 'CustomerPayBillOnline',
                                    'Amount' => $Amount,
                                    'PartyA' => $PartyA,
                                    'PartyB' => $BusinessShortCode,
                                    'PhoneNumber' => $PartyA,
                                    'CallBackURL' => $callbackUrl,
                                    'AccountReference' => $AccountReference,
                                    'TransactionDesc' => $TransactionDesc
                                );

                                $data_string = json_encode($curl_post_data);

                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($curl, CURLOPT_POST, true);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

                                $curl_response = curl_exec($curl);

                                // Check the return value of curl_exec(), too
                                if ($curl_response === false) {
                                    echo ' ' . curl_error($curl), curl_errno($curl);
                                }
                                //print_r($curl_response);

                                //echo $curl_response;
                                $cresult = $curl_response;
                                //var_dump($cresult);
                                //$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                                $cresult = json_decode($cresult);
                                //var_dump($cresult);
                                //echo '</br>';
                                //echo $MerchantRequestID = $cresult->MerchantRequestID;
                                //echo '</br>';
                                if (isset($cresult->ResponseCode)) {
                                    //echo $crid = $cresult->CheckoutRequestID;
                                    // Set session variables
                                    $_SESSION["crid"] = $cresult->CheckoutRequestID;
                                    //$cridx = $cresult->CheckoutRequestID;
                                } else {
                                    $_SESSION["crid"] = "null";
                                    // echo '</br>';
                                    echo '<div  class="alert alert-warning alert-dismissible text-center" id="countjob">' .
                                        '<button type="button" class="close">&times;</button> '
                                        . '  Another Transaction is in process cancel it on phone and go back and try again</div>';
                                }
                                //echo '</br>';
                                //echo $ResponseCode = $cresult->ResponseCode;
                                //echo '</br>';
                                //echo $ResponseDescription = $cresult->ResponseDescription;
                                //echo '</br>';
                                //echo $CustomerMessage = $cresult->CustomerMessage;

                                echo '</br>';
                                echo '<div  class="alert alert-success alert-dismissible text-center" id="countjob">' .
                                    '<button type="button" class="close">&times;</button> '
                                    . ' - Unlock phone and enter mpesa pin to finalize payment </div>';

                                echo '<div  class="alert alert-success alert-dismissible text-center" id="countjob">'
                                    . '<button type="button" class="close">&times;</button> '
                                    . ' - Do not close or refresh this page. This page will redirect automatically after 20 '
                                    . 'seconds to check if payment has been received </div>';


                                curl_close($curl);
                                $paid =1;
                                $a_id=$_POST['a_id'];
                                $query = "UPDATE appointment SET paid=? WHERE id=?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("ss", $paid, $a_id);
                                $stmt->execute();
                                $_SESSION['response'] = "Updated Succesfully!";
                                $_SESSION['res_type'] = "primary";
                                //Set Refresh header using PHP.
                                header("refresh:20;url=pappointments.php");
                                ?>





                            </div>

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