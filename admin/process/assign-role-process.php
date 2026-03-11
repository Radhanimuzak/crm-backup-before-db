<?php

session_start();

require "../../config/database.php";
require "../../config/mail.php";

/* ambil data */

$user_id = $_POST['user_id'];
$role_id = $_POST['role_id'];


/* generate token */

$token = bin2hex(random_bytes(32));


/* update database */

$query = "UPDATE users SET

role_id = '$role_id',
verify_token = '$token'

WHERE user_id = '$user_id'";

mysqli_query($conn,$query);


/* ambil email + role name */

$user_query = mysqli_query($conn,"
SELECT 
users.user_email,
roles.role_name
FROM users
LEFT JOIN roles ON users.role_id = roles.role_id
WHERE users.user_id='$user_id'
");

$user = mysqli_fetch_assoc($user_query);

$email = $user['user_email'];
$role_name = $user['role_name'];


/* buat link create password */

$link = "http://localhost/kaiadmin-lite-1.2.0/auth/set-password.php?token=".$token;


/* ambil template email */

$template = file_get_contents("../../email_templates/user_role_assigned.html");


/* replace template */

$template = str_replace("{{create_password_link}}",$link,$template);
$template = str_replace("{{role}}",$role_name,$template);


/* kirim email */

sendMail(
$email,
"Your CRM Account Has Been Activated",
$template
);


/* redirect */

header("Location: ../dashboard.php");
exit();

?>