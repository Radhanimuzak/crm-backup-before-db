<?php

/*
|--------------------------------------------------------------------------
| TIMEZONE SETTING
|--------------------------------------------------------------------------
| System akan mengikuti waktu Malaysia
*/

date_default_timezone_set("Asia/Kuala_Lumpur");


/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$host = "localhost";
$user = "root";
$password = "";
$database = "crm_system";


$conn = mysqli_connect($host, $user, $password, $database);


/*
|--------------------------------------------------------------------------
| CONNECTION CHECK
|--------------------------------------------------------------------------
*/

if (!$conn) {

    die("Database connection failed: " . mysqli_connect_error());

}

?>