<?php

session_start();

require "../../config/database.php";
require "../../config/mail.php";

$email = $_POST['email'];

/* CHECK EMAIL */

$query = mysqli_query($conn,"SELECT * FROM users WHERE user_email='$email'");

if(mysqli_num_rows($query)==0){

$_SESSION['forgot_error'] = "Email not found";

header("Location: ../login.php");
exit();

}

$user = mysqli_fetch_assoc($query);

/* GENERATE TOKEN */

$token = bin2hex(random_bytes(32));

$expire = date("Y-m-d H:i:s", strtotime("+15 minutes"));

/* UPDATE DATABASE */

mysqli_query($conn,"UPDATE users SET

verify_token='$token',
token_expired_at='$expire'

WHERE user_email='$email'");

/* CREATE RESET LINK */

$link = "http://localhost/kaiadmin-lite-1.2.0/auth/reset-password.php?token=".$token;

/* LOAD EMAIL TEMPLATE */

$template = file_get_contents("../../email_templates/reset_password.html");

$template = str_replace("{{reset_link}}",$link,$template);

/* SEND EMAIL */

sendMail($email,"Reset Password",$template);

/* SUCCESS MESSAGE */

$_SESSION['forgot_success'] = true;

header("Location: ../login.php");
exit();

?>