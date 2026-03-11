<?php

session_start();
require "../config/database.php";

/* AMBIL USER YANG MENUNGGU ROLE */

$notifQuery = mysqli_query($conn,"
SELECT user_id, user_name
FROM users
WHERE user_status='inactive'
ORDER BY created_at DESC
");

$notifCount = mysqli_num_rows($notifQuery);

?>

<!DOCTYPE html>

<html lang="en">
<head>

<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>CRM Admin Dashboard</title>

<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>

<script src="../assets/js/plugin/webfont/webfont.min.js"></script>

<script>
WebFont.load({
google:{families:["Public Sans:300,400,500,600,700"]},
custom:{
families:[
"Font Awesome 5 Solid",
"Font Awesome 5 Regular",
"Font Awesome 5 Brands",
"simple-line-icons"
],
urls:["../assets/css/fonts.min.css"]
},
active:function(){
sessionStorage.fonts=true;
}
});
</script>

<link rel="stylesheet" href="../assets/css/bootstrap.min.css"/>
<link rel="stylesheet" href="../assets/css/plugins.min.css"/>
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css"/>

<style>

.logo-header{
height:95px;
display:flex;
align-items:center;
padding-left:20px;
}

.logo-header .logo img{
height:48px;
transform:scale(1.6);
transform-origin:left center;
}

.sidebar{
background:linear-gradient(180deg,#1e293b 0%,#0f172a 100%);
}

.sidebar .nav > .nav-item > a{
border-radius:8px;
margin:4px 8px;
}

.sidebar .nav > .nav-item.active > a{
background:rgba(255,255,255,0.08);
}

</style>

</head>

<body>

<div class="wrapper">

<!-- SIDEBAR -->

<div class="sidebar" data-background-color="dark">

<div class="sidebar-logo">

<div class="logo-header" data-background-color="dark">

<a href="dashboard.php" class="logo">
<img src="../assets/img/logo_crm.png" alt="CRM Logo">
</a>

<div class="nav-toggle">

<button class="btn btn-toggle toggle-sidebar">
<i class="gg-menu-right"></i>
</button>

<button class="btn btn-toggle sidenav-toggler">
<i class="gg-menu-left"></i>
</button>

</div>

<button class="topbar-toggler more">
<i class="gg-more-vertical-alt"></i>
</button>

</div>

</div>

<div class="sidebar-wrapper scrollbar scrollbar-inner">

<div class="sidebar-content">

<ul class="nav nav-secondary">

<li class="nav-item active">
<a href="dashboard.php">
<i class="fas fa-home"></i>
<p>Dashboard</p>
</a>
</li>

<li class="nav-item">
<a href="users.php">
<i class="fas fa-users"></i>
<p>User List</p>
</a>
</li>

<li class="nav-item">
<a href="user-role-assignment.php">
<i class="fas fa-user-check"></i>
<p>User Role Assignment</p>
</a>
</li>

<li class="nav-item">
<a href="../auth/login.php">
<i class="fas fa-sign-out-alt"></i>
<p>Logout</p>
</a>
</li>

</ul>

</div>

</div>

</div>

<!-- MAIN PANEL -->

<div class="main-panel">

<div class="main-header">

<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">

<div class="container-fluid">

<!-- SEARCH -->

<nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">

<div class="input-group">

<div class="input-group-prepend">
<button type="submit" class="btn btn-search pe-1">
<i class="fa fa-search search-icon"></i>
</button>
</div>

<input type="text" placeholder="Search ..." class="form-control"/>

</div>

</nav>

<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

<!-- NOTIFICATION -->

<li class="nav-item topbar-icon dropdown hidden-caret">

<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">

<i class="fa fa-bell"></i>

<?php if($notifCount > 0){ ?>
<span class="notification"><?php echo $notifCount; ?></span>
<?php } ?>

</a>

<ul class="dropdown-menu notif-box animated fadeIn">

<li>
<div class="dropdown-title">
You have <?php echo $notifCount; ?> user waiting for role assignment
</div>
</li>

<li>

<div class="notif-scroll scrollbar-outer">

<div class="notif-center">

<?php while($user = mysqli_fetch_assoc($notifQuery)){ ?>

<a href="user-role-assignment.php?user_id=<?php echo $user['user_id']; ?>">

<div class="notif-icon notif-primary">
<i class="fa fa-user-plus"></i>
</div>

<div class="notif-content">

<span class="block">
<?php echo $user['user_name']; ?> registered
</span>

<span class="time">
Waiting admin to assign role
</span>

</div>

</a>

<?php } ?>

</div>

</div>

</li>

</ul>

</li>

<!-- PROFILE -->

<li class="nav-item topbar-user dropdown hidden-caret">

<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">

<div class="avatar-sm">
<img src="../assets/img/profile.jpg" class="avatar-img rounded-circle"/>
</div>

<span class="profile-username">
<span class="op-7">Hi,</span>
<span class="fw-bold">Admin</span>
</span>

</a>

</li>

</ul>

</div>

</nav>

</div>

<div class="container">

<div class="page-inner">

<h4 class="page-title">Admin Dashboard</h4>

<div class="row">

<div class="col-md-4">
<div class="card card-stats card-round">
<div class="card-body">
<div class="row align-items-center">

<div class="col-icon">
<div class="icon-big text-center icon-primary bubble-shadow-small">
<i class="fas fa-user-clock"></i>
</div>
</div>

<div class="col col-stats ms-3">
<div class="numbers">
<p class="card-category">Pending Approval</p>
<h4 class="card-title"><?php echo $notifCount; ?></h4>
</div>
</div>

</div>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card card-stats card-round">
<div class="card-body">
<div class="row align-items-center">

<div class="col-icon">
<div class="icon-big text-center icon-success bubble-shadow-small">
<i class="fas fa-user-check"></i>
</div>
</div>

<div class="col col-stats ms-3">
<div class="numbers">
<p class="card-category">Approved Users</p>
<h4 class="card-title">
<?php

$approved = mysqli_query($conn,"SELECT COUNT(*) as total FROM users WHERE user_status='active'");
$row = mysqli_fetch_assoc($approved);
echo $row['total'];

?>
</h4>
</div>
</div>

</div>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card card-stats card-round">
<div class="card-body">
<div class="row align-items-center">

<div class="col-icon">
<div class="icon-big text-center icon-info bubble-shadow-small">
<i class="fas fa-users"></i>
</div>
</div>

<div class="col col-stats ms-3">
<div class="numbers">
<p class="card-category">Total Users</p>
<h4 class="card-title">
<?php

$total = mysqli_query($conn,"SELECT COUNT(*) as total FROM users");
$row = mysqli_fetch_assoc($total);
echo $row['total'];

?>
</h4>
</div>
</div>

</div>
</div>
</div>
</div>

</div>

</div>

</div>

<footer class="footer">
<div class="container-fluid">
CRM System Admin Dashboard
</div>
</footer>

</div>

</div>

<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>

</body>
</html>