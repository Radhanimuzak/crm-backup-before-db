<?php

session_start();

require "../../config/database.php";
require "../../config/mail.php";

$user_name = $_POST['user_name'];
$user_fullname = $_POST['user_fullname'];
$user_email = $_POST['user_email'];
$user_mobile_no = $_POST['user_mobile_no'];
$user_position = $_POST['user_position'];
$division_id = $_POST['division_id'];
$unit_id = $_POST['unit_id'];


/* CHECK USERNAME */

$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_name = ?");
$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){

$_SESSION['register_error'] = "Username already used";
header("Location: ../register.php");
exit();

}


/* CHECK EMAIL */

$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0){

$_SESSION['register_error'] = "Email already registered";
header("Location: ../register.php");
exit();

}


/* INSERT USER */

$stmt = $conn->prepare("

INSERT INTO users(

user_name,
user_fullname,
user_email,
user_mobile_no,
user_position,
division_id,
unit_id,
user_status,
created_at,
updated_at

)

VALUES(?,?,?,?,?,?,?,'inactive',NOW(),NOW())

");

$stmt->bind_param(
"sisssii",
$user_name,
$user_fullname,
$user_email,
$user_mobile_no,
$user_position,
$division_id,
$unit_id
);

$stmt->execute();


/* GET USER ID */

$user_id = $conn->insert_id;


/* GET DIVISION NAME */

$division_query = mysqli_query($conn,"SELECT division_name FROM divisions WHERE division_id='$division_id'");
$division = mysqli_fetch_assoc($division_query);
$division_name = $division['division_name'];


/* GET UNIT NAME */

$unit_query = mysqli_query($conn,"SELECT unit_name FROM units WHERE unit_id='$unit_id'");
$unit = mysqli_fetch_assoc($unit_query);
$unit_name = $unit['unit_name'];


/* CREATE LINK */

$link = "http://localhost/kaiadmin-lite-1.2.0/admin/user-role-assignment.php?user_id=".$user_id;


/* LOAD TEMPLATE */

$template = file_get_contents("../../email_templates/admin_new_user.html");

$template = str_replace("{{username}}",$user_name,$template);
$template = str_replace("{{fullname}}",$user_fullname,$template);
$template = str_replace("{{email}}",$user_email,$template);
$template = str_replace("{{mobile}}",$user_mobile_no,$template);
$template = str_replace("{{position}}",$user_position,$template);
$template = str_replace("{{division}}",$division_name,$template);
$template = str_replace("{{unit}}",$unit_name,$template);
$template = str_replace("{{assign_link}}",$link,$template);


/* GET ADMIN EMAIL */

$admins = mysqli_query($conn,"SELECT user_email FROM users WHERE role_id=1");


/* SEND EMAIL */

while($admin = mysqli_fetch_assoc($admins)){

sendMail(
$admin['user_email'],
"New User Registration",
$template
);

}


$_SESSION['register_success'] = true;

header("Location: ../register.php");
exit();

?>