<?php

require "../../config/database.php";

$token = $_POST['token'];
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];


/* VALIDATE PASSWORD */

if($password !== $confirm){

echo "Password does not match";
exit();

}


/* HASH PASSWORD */

$hash = password_hash($password,PASSWORD_DEFAULT);


/* CHECK TOKEN */

$query = mysqli_query($conn,"
SELECT * FROM users
WHERE verify_token='$token'
");

if(mysqli_num_rows($query)==0){

echo "Invalid token";
exit();

}


/* UPDATE PASSWORD */

mysqli_query($conn,"
UPDATE users SET

user_password='$hash',
user_status='active',
verify_token=NULL,
token_expired_at=NULL,
updated_at = NOW()

WHERE verify_token='$token'
");

echo "success";

?>