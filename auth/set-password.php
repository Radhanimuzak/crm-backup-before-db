<?php

require "../config/database.php";

if(!isset($_GET['token'])){
echo "Invalid link";
exit();
}

$token = $_GET['token'];

$query = mysqli_query($conn,"SELECT * FROM users WHERE verify_token='$token'");

if(mysqli_num_rows($query)==0){

echo "<h2 style='text-align:center;margin-top:100px;font-family:Arial'>
Password already created or link invalid
</h2>";

exit();

}

$user = mysqli_fetch_assoc($query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

body{
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
height:100vh;
display:flex;
align-items:center;
justify-content:center;
}

.card{
width:420px;
padding:40px;
border-radius:18px;
}

.btn-kai-dark{
background:#2a2f5b;
border:none;
color:#fff;
height:48px;
font-weight:500;
}

.btn-kai-dark:hover{
background:#111628;
}

.form-control{
height:48px;
}

.input-group-text{
cursor:pointer;
}

.success-icon{
width:110px;
height:110px;
border-radius:50%;
border:4px solid #28a745;
display:flex;
align-items:center;
justify-content:center;
margin:auto;
margin-bottom:20px;
position:relative;
}

.success-icon::after{
content:'';
width:32px;
height:60px;
border-right:6px solid #28a745;
border-bottom:6px solid #28a745;
transform:rotate(45deg);
animation:checkAnim .6s ease forwards;
opacity:0;
}

@keyframes checkAnim{

0%{
opacity:0;
transform:scale(.5) rotate(45deg);
}

100%{
opacity:1;
transform:scale(1) rotate(45deg);
}

}

.modal-content{
border-radius:14px;
}

</style>
</head>

<body>

<div class="card shadow">

<h3 class="fw-bold mb-3 text-center">
Create Your Password
</h3>

<p class="text-muted text-center mb-4">
Your account has been approved.<br>
Please create a password to activate your account.
</p>

<form id="setPasswordForm" method="POST">

<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="form-group mb-3">

<label>New Password</label>

<div class="input-group">

<input type="password"
name="password"
id="password"
class="form-control"
placeholder="Enter new password"
required>

<button type="button"
class="input-group-text bg-white"
onclick="togglePassword('password','eye1')">

<i class="fa-solid fa-eye" id="eye1"></i>

</button>

</div>

</div>


<div class="form-group mb-4">

<label>Confirm Password</label>

<div class="input-group">

<input type="password"
name="confirm_password"
id="confirm_password"
class="form-control"
placeholder="Confirm password"
required>

<button type="button"
class="input-group-text bg-white"
onclick="togglePassword('confirm_password','eye2')">

<i class="fa-solid fa-eye" id="eye2"></i>

</button>

</div>

</div>

<button class="btn btn-kai-dark w-100">
Set Password
</button>

</form>

</div>


<!-- SUCCESS MODAL -->

<div class="modal fade" id="successModal">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-body text-center p-4">

<div class="success-icon"></div>

<h4 class="fw-bold">
Password Created
</h4>

<p class="text-muted">
Your password has been successfully created.
</p>

<button
class="btn btn-kai-dark mt-3"
onclick="redirectLogin()">

Go to Login

</button>

</div>

</div>

</div>

</div>



<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>

<script>

function togglePassword(fieldId,iconId){

var input=document.getElementById(fieldId);
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



document.getElementById("setPasswordForm").addEventListener("submit",function(e){

e.preventDefault();

var pass=document.getElementById("password").value;
var confirm=document.getElementById("confirm_password").value;

if(pass!==confirm){

alert("Password does not match!");
return;

}


/* AJAX SAVE PASSWORD */

$.post("process/set-password-process.php",$(this).serialize(),function(){

showSuccess();

});

});


function showSuccess(){

var success=new bootstrap.Modal(document.getElementById('successModal'));
success.show();

}


function redirectLogin(){

window.location.href="login.php";

}

</script>

</body>
</html>