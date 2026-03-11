<!DOCTYPE html>
<html lang="en">
<head>

<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Staff Dashboard</title>

<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>

<!-- BIARKAN FAVICON XAMPP -->
<!-- <link rel="icon" href="../assets/img/kaiadmin/favicon.ico" type="image/x-icon"/> -->

<!-- Fonts -->
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

/* LOGO STYLE SAMA SEMUA ROLE */

.logo-header{
height:95px;
display:flex;
align-items:center;
justify-content:flex-start;
padding-left:20px;
position:relative;
}

.logo-header .logo img{
height:48px;
transform:scale(1.6);
transform-origin:left center;
}

/* SIDEBAR STYLE */

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
<a href="#">
<i class="fas fa-tasks"></i>
<p>My Tasks</p>
</a>
</li>

<li class="nav-item">
<a href="#">
<i class="fas fa-user-friends"></i>
<p>My Customers</p>
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
<!-- END SIDEBAR -->


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

<input
type="text"
placeholder="Search ..."
class="form-control"
/>

</div>

</nav>

<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

<li class="nav-item topbar-user dropdown hidden-caret">

<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">

<div class="avatar-sm">
<img src="../assets/img/profile.jpg" class="avatar-img rounded-circle"/>
</div>

<span class="profile-username">
<span class="op-7">Hi,</span>
<span class="fw-bold">Staff</span>
</span>

</a>

</li>

</ul>

</div>

</nav>

</div>


<!-- CONTENT -->
<div class="container">

<div class="page-inner">

<div class="page-header">

<h4 class="page-title">Staff Dashboard</h4>

<ul class="breadcrumbs">

<li class="nav-home">
<i class="icon-home"></i>
</li>

<li class="separator">
<i class="icon-arrow-right"></i>
</li>

<li class="nav-item">
Staff
</li>

<li class="separator">
<i class="icon-arrow-right"></i>
</li>

<li class="nav-item">
Dashboard
</li>

</ul>

</div>


<!-- STAFF CONTENT -->

<div class="card">

<div class="card-body text-center">

<h3>Welcome to CRM Staff</h3>

<p class="text-muted">
Staff dashboard will display assigned tasks and customers.
</p>

</div>

</div>

</div>

</div>


<footer class="footer">

<div class="container-fluid d-flex justify-content-between">

<div class="copyright">
CRM System Dashboard
</div>

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