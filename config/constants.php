<?php
// Sending email details
define('EMAIL', 'testuser@gmail.com');
define('PASSWORD', 'testpass');

$rpath = getcwd();
//echo $rpath;
date_default_timezone_set("Africa/Nairobi");
/*  $script_tz = date_default_timezone_get();

if (strcmp($script_tz, ini_get('date.timezone'))){
    echo 'Script timezone '.$script_tz.'differs from ini-set timezone '.ini_get('date.timezone');
} else {
    echo 'Script timezone '.$script_tz.' and ini-set timezone '.ini_get('date.timezone').' match.';
}  */
class Constants
{

    const DB_HOST = "localhost";

    const DB_USERNAME = "root";

    const DB_PASWORD = "";
    const DB_NAME = "dental";

    const SMTP_HOST = '';

    const SMTP_USER = '';

    const SMTP_PASSWORD = '';

    //const SMTP_PORT = 587;
}
