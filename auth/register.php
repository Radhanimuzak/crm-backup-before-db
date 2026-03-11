<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Register - CRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<style>

body{
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
padding:40px 0;
}

.register-wrapper{
max-width:560px;
margin:auto;
}

.register-card{
border-radius:18px;
padding:40px;
border:none;
}

.section-title{
font-size:14px;
font-weight:600;
color:#6c757d;
margin-top:20px;
margin-bottom:10px;
}

.form-control,
.form-select{
height:48px;
border-radius:8px;
}

.btn-kai-dark{
background:#2a2f5b;
border:none;
color:#fff;
height:48px;
font-weight:500;
border-radius:8px;
}

.btn-kai-dark:hover{
background:#111628;
}

.input-icon{
position:relative;
}

.input-icon i{
position:absolute;
top:50%;
left:12px;
transform:translateY(-50%);
color:#999;
}

.input-icon input{
padding-left:36px;
}

/* SUCCESS ICON */

.success-icon{
width:120px;
height:120px;
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
width:35px;
height:65px;
border-right:6px solid #28a745;
border-bottom:6px solid #28a745;
transform:rotate(45deg);
}

.modal-content{
border-radius:14px;
}

</style>

</head>

<body>

<div class="container register-wrapper">

<div class="card shadow register-card">

<h3 class="fw-bold mb-2">Create Account</h3>

<p class="text-muted mb-3">
Fill in the information below to register your account
</p>

<?php
if(isset($_SESSION['register_error'])){
?>

<div class="alert alert-danger">
<?php
echo $_SESSION['register_error'];
unset($_SESSION['register_error']);
?>
</div>

<?php
}
?>

<form method="POST" action="process/register-process.php">

<div class="section-title">Account Information</div>

<div class="form-group mb-3 input-icon">
<i class="fa fa-user"></i>
<input type="text" name="user_name" class="form-control" placeholder="Username" required>
</div>

<div class="form-group mb-3 input-icon">
<i class="fa fa-id-card"></i>
<input type="text" name="user_fullname" class="form-control" placeholder="Full Name" required>
</div>

<div class="form-group mb-3 input-icon">
<i class="fa fa-envelope"></i>
<input type="email" name="user_email" class="form-control" placeholder="Email Address" required>
</div>

<div class="form-group mb-3 input-icon">
<i class="fa fa-phone"></i>
<input type="text" name="user_mobile_no" class="form-control" placeholder="Mobile Number" required>
</div>

<div class="section-title">Work Information</div>

<div class="form-group mb-3 input-icon">
<i class="fa fa-briefcase"></i>
<input type="text" name="user_position" class="form-control" placeholder="Position" required>
</div>

<div class="form-group mb-3">

<select name="division_id" class="form-select" required>

<option value="">Select Division</option>
<option value="1">Perkhidmatan Langganan</option>
<option value="2">Jualan Digital</option>
<option value="3">IT</option>
<option value="4">HR</option>
<option value="5">Bold</option>
<option value="6">Khidmat Pelanggan</option>

</select>

</div>

<div class="form-group mb-4">

<select name="unit_id" class="form-select" required>

<option value="">Select Unit</option>
<option value="1">Pangkalan Data</option>
<option value="2">Jualan</option>
<option value="3">Software Development</option>
<option value="4">Recruitment</option>
<option value="5">UKP</option>

</select>

</div>

<button type="submit" class="btn btn-kai-dark w-100 mb-3">
Register
</button>

<div class="text-center">

<span class="text-muted small">
Already have an account?
</span>

<a href="login.php" class="text-primary fw-semibold small">
Login
</a>

</div>

</form>

</div>
</div>

<!-- SUCCESS MODAL -->

<div class="modal fade" id="registerSuccess">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-body text-center p-4">

<div class="success-icon"></div>

<h4 class="fw-bold mb-2">
Registration Successful
</h4>

<p class="text-muted">

Your registration has been submitted.  
Please wait for the administrator to assign your role.  

You will receive an email to create your password.

</p>

<a href="login.php" class="btn btn-kai-dark mt-3">
Go to Login
</a>

</div>

</div>
</div>
</div>

<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>

<?php
if(isset($_SESSION['register_success'])){
?>

<script>
var myModal = new bootstrap.Modal(document.getElementById('registerSuccess'));
myModal.show();
</script>

<?php
unset($_SESSION['register_success']);
}
?>

</body>
</html>