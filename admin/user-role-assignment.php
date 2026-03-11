<?php

require "../config/database.php";

/* cek apakah ada user_id */

if(!isset($_GET['user_id'])){
echo "User not found";
exit();
}

$user_id = $_GET['user_id'];

/* ambil data user + division + unit */

$query = mysqli_query($conn,"
SELECT 
users.*, 
divisions.division_name, 
units.unit_name
FROM users
LEFT JOIN divisions ON users.division_id = divisions.division_id
LEFT JOIN units ON users.unit_id = units.unit_id
WHERE users.user_id='$user_id'
");

$user = mysqli_fetch_assoc($query);

/* cek user ada atau tidak */

if(!$user){
echo "User not found";
exit();
}

/* cek apakah role sudah diberikan */

if($user['role_id'] != NULL){

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Role Already Assigned</title>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css">

</head>

<body style="background:#f5f7fd;font-family:Arial">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="card shadow mt-5">

<div class="card-body text-center p-5">

<h3 class="fw-bold mb-3">
Role Already Assigned
</h3>

<p class="text-muted">
You have already assigned a role to this user.
This registration request has been completed.
</p>

</div>

</div>

</div>

</div>

</div>

</body>
</html>

<?php
exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Assign User Role</title>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css">

<style>

body{
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
padding:40px;
}

.card{
border-radius:16px;
border:none;
}

.section-title{
font-size:14px;
color:#6c757d;
margin-bottom:4px;
}

.info-value{
font-weight:600;
font-size:15px;
}

.info-box{
padding:12px 0;
border-bottom:1px solid #f1f1f1;
}

.role-box{
background:#f8f9fc;
padding:20px;
border-radius:12px;
margin-top:20px;
}

.btn-submit{
background:#2a2f5b;
color:white;
border:none;
height:45px;
padding:0 28px;
border-radius:8px;
}

.btn-cancel{
background:#dee2e6;
border:none;
height:45px;
padding:0 28px;
border-radius:8px;
}

.modal-content{
border-radius:14px;
}

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
animation:checkAnim 0.6s ease forwards;
opacity:0;
}

@keyframes checkAnim{
0%{opacity:0; transform:scale(0.5) rotate(45deg);}
100%{opacity:1; transform:scale(1) rotate(45deg);}
}

</style>

</head>

<body>

<div class="container">
<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card shadow">

<div class="card-body p-4">

<h3 class="fw-bold mb-2">
Assign User Role
</h3>

<p class="text-muted mb-4">
Review the user information and assign the appropriate role.
</p>

<hr>

<div class="row">

<div class="col-md-6 info-box">
<div class="section-title">Username</div>
<div class="info-value"><?php echo $user['user_name']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Full Name</div>
<div class="info-value"><?php echo $user['user_fullname']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Email</div>
<div class="info-value"><?php echo $user['user_email']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Mobile Number</div>
<div class="info-value"><?php echo $user['user_mobile_no']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Position</div>
<div class="info-value"><?php echo $user['user_position']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Division</div>
<div class="info-value"><?php echo $user['division_name']; ?></div>
</div>

<div class="col-md-6 info-box">
<div class="section-title">Unit</div>
<div class="info-value"><?php echo $user['unit_name']; ?></div>
</div>

</div>

<form method="POST" action="process/assign-role-process.php">

<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

<div class="role-box">

<label class="form-label fw-semibold mb-2">
Assign Role
</label>

<select name="role_id" id="roleSelect" class="form-select">

<option value="">Select Role</option>
<option value="1">Admin</option>
<option value="2">Manager</option>
<option value="3">Staff</option>

</select>

</div>

<div class="d-flex gap-3 mt-4">

<button type="button" class="btn btn-submit" onclick="checkRole()">
Submit
</button>

<button type="button" class="btn btn-cancel" onclick="history.back()">
Cancel
</button>

</div>

</form>

</div>
</div>
</div>
</div>
</div>


<!-- WARNING -->

<div class="modal fade" id="roleWarning">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">
<h5 class="modal-title fw-bold">Role Required</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
Please select a role before submitting.
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">
OK
</button>
</div>

</div>
</div>
</div>


<!-- SUCCESS -->

<div class="modal fade" id="roleSuccess">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-body text-center">

<div class="success-icon"></div>

<h4 class="fw-bold mt-3">
Role Assigned Successfully
</h4>

<p class="text-muted">

The user role has been successfully assigned.

An email will be sent to the registered email address with instructions to create a password.

</p>

<button class="btn btn-submit mt-3" onclick="submitForm()">
OK
</button>

</div>

</div>
</div>
</div>


<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>

<script>

function checkRole(){

let role = document.getElementById("roleSelect").value;

if(role === ""){

let warning = new bootstrap.Modal(document.getElementById('roleWarning'));
warning.show();

}else{

let successModal = new bootstrap.Modal(document.getElementById('roleSuccess'));
successModal.show();

}

}

function submitForm(){

document.querySelector("form").submit();

}

</script>

</body>
</html>