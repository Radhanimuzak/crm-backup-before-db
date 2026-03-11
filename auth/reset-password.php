<?php

require "../config/database.php";

$token = $_GET['token'] ?? '';

$query = mysqli_query($conn,"
SELECT * FROM users
WHERE verify_token='$token'
AND token_expired_at > NOW()
");

if(mysqli_num_rows($query)==0){
echo "Invalid or expired token";
exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - CRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<style>

body{
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
}

.reset-card{
border-radius:18px;
}

.btn-kai-dark{
background:#2A2F5B;
border:none;
color:#fff;
height:48px;
font-weight:500;
}

.btn-kai-dark:hover{
background:#1f2447;
color:#fff;
}

.form-control{
height:48px;
}

.input-group-text{
cursor:pointer;
}

</style>
</head>

<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">

<div class="card shadow-lg p-5 reset-card"
style="max-width:450px;width:100%;">

<h4 class="fw-bold mb-4">Create New Password</h4>

<form method="POST"
action="process/reset-password-process.php"
onsubmit="return validatePassword()">

<input type="hidden" name="token" value="<?php echo $token; ?>">

<!-- NEW PASSWORD -->

<div class="mb-3">

<label class="mb-2">New Password</label>

<div class="input-group">

<input type="password"
class="form-control"
name="password"
id="newPassword"
placeholder="Enter new password"
required>

<button type="button"
class="input-group-text bg-white"
onclick="togglePassword('newPassword','eye1')">

<i class="fa-solid fa-eye" id="eye1"></i>

</button>

</div>

</div>

<!-- CONFIRM PASSWORD -->

<div class="mb-4">

<label class="mb-2">Confirm Password</label>

<div class="input-group">

<input type="password"
class="form-control"
name="confirm_password"
id="confirmPassword"
placeholder="Re-enter password"
required>

<button type="button"
class="input-group-text bg-white"
onclick="togglePassword('confirmPassword','eye2')">

<i class="fa-solid fa-eye" id="eye2"></i>

</button>

</div>

</div>

<button type="submit"
class="btn btn-kai-dark w-100">

Update Password

</button>

</form>

</div>
</div>

<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>

<script>

function togglePassword(inputId,iconId){

var input=document.getElementById(inputId);
var icon=document.getElementById(iconId);

if(input.type==="password"){
input.type="text";
icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");
}else{
input.type="password";
icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");
}

}

function validatePassword(){

let pass=document.getElementById("newPassword").value;
let confirm=document.getElementById("confirmPassword").value;

if(pass !== confirm){

alert("Password and Confirm Password must match");
return false;

}

return true;

}

</script>

</body>
</html>