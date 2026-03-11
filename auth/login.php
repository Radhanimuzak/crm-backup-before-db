<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - CRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<style>

body{
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
}

.login-card{
border-radius:18px;
overflow:hidden;
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

@media (max-width:768px){
.right-image{
display:none;
}

}

</style>
</head>

<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">

<div class="card shadow-lg login-card" style="max-width:880px;width:100%;">

<div class="row g-0">

<!-- LOGIN FORM -->

<div class="col-md-6 p-5">

<h3 class="fw-bold mb-2">Login to Your Account</h3>

<p class="text-muted mb-4">
Enter your credentials to access your account
</p>

<!-- LOGIN ERROR -->

<?php
if(isset($_SESSION['error'])){
?>

<div class="alert alert-danger">
<?php
echo $_SESSION['error'];
unset($_SESSION['error']);
?>
</div>

<?php
}
?>

<!-- RESET SUCCESS -->

<?php
if(isset($_SESSION['forgot_success'])){
?>

<div class="alert alert-success">
Reset password link has been sent to your email.
</div>

<?php
unset($_SESSION['forgot_success']);
}
?>

<!-- EMAIL ERROR -->

<?php
if(isset($_SESSION['forgot_error'])){
?>

<div class="alert alert-danger">
<?php
echo $_SESSION['forgot_error'];
unset($_SESSION['forgot_error']);
?>
</div>

<?php
}
?>

<form method="POST" action="process/login-process.php">

<div class="form-group mb-3">

<label>Username</label>

<input type="text"
name="username"
class="form-control"
placeholder="Enter username"
required>

</div>

<div class="form-group mb-2">

<label>Password</label>

<div class="input-group">

<input type="password"
name="password"
class="form-control"
placeholder="Enter password"
required>

<button type="button"
class="input-group-text bg-white"
onclick="togglePassword()">

<i class="fa-solid fa-eye" id="eyeIcon"></i>

</button>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-4">

<div class="form-check">
<input class="form-check-input" type="checkbox">
<label class="form-check-label">
Remember me
</label>
</div>

<a href="#" class="text-primary small"
data-bs-toggle="modal"
data-bs-target="#forgotModal">

Forgot password?

</a>

</div>

<button type="submit"
class="btn btn-kai-dark w-100 mb-3">

Login

</button>

<div class="text-center">

<span class="text-muted small">
Don't have an account?
</span>

<a href="register.php"
class="text-primary fw-semibold small">

Sign up

</a>

</div>

</form>

</div>

<!-- IMAGE -->

<div class="col-md-6 right-image">

<img src="/kaiadmin-lite-1.2.0/assets/img/logo_login.png"
class="img-fluid h-100 w-100"
style="object-fit:cover;"
alt="CRM Login">

</div>

</div>
</div>
</div>


<!-- FORGOT PASSWORD MODAL -->

<div class="modal fade" id="forgotModal">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
Forgot Password
</h5>

<button type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<p class="text-muted">
Enter your email address and we will send you a link to reset your password.
</p>

<form method="POST" action="process/forgot-process.php">

<div class="mb-3">

<label>Email Address</label>

<input type="email"
name="email"
class="form-control"
placeholder="Enter your email"
required>

</div>

<button type="submit"
class="btn btn-kai-dark w-100">

Send Reset Link

</button>

</form>

</div>

</div>

</div>

</div>


<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>

<script>

function togglePassword(){

var password=document.querySelector("input[name='password']");
var icon=document.getElementById("eyeIcon");

if(password.type==="password"){
password.type="text";
icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");
}else{
password.type="password";
icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");
}

}

</script>

</body>
</html>