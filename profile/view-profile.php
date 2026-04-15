<?php
session_start();
require "../config/auth-check.php";
require "../config/database.php";


/* VALIDASI SESSION */
if(empty($_SESSION['user_id'])){
    die("Session tidak valid / belum login");
}

/* ================= USER ================= */
$user_id = (int) $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT 
        u.*, 
        r.role_name,
        d.division_name,
        un.unit_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.role_id
    LEFT JOIN divisions d ON u.division_id = d.division_id
    LEFT JOIN units un ON u.unit_id = un.unit_id
    WHERE u.user_id = $user_id
");

if(!$query){
    die("Query error: " . mysqli_error($conn));
}

$profile = mysqli_fetch_assoc($query) ?? [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Profile</title>

    <!-- ===== HEADER (CSS + META) ===== -->
    <?php include('../includes/header.php'); ?>

    <style>
        .profile-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .profile-card {
            background: #fff;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f1f5f9;
            cursor: pointer;
            transition: 0.3s;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .profile-name {
            font-size: 22px;
            font-weight: 600;
            margin-top: 15px;
        }

        .profile-role {
            display: inline-block;
            margin-top: 5px;
            padding: 5px 14px;
            border-radius: 20px;
            background: #e7f1ff;
            color: #0d6efd;
            font-size: 13px;
        }

        .section-title {
            font-weight: 600;
            margin: 25px 0 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            background: #f9fafb;
            padding: 12px 15px;
            border-radius: 8px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
        }

        .info-value {
            font-weight: 600;
            margin-top: 3px;
        }

        .image-modal {
            display: none;
            position: fixed;
            z-index: 999;
            padding-top: 80px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            text-align: center;
        }

        .image-modal img {
            max-width: 400px;
            border-radius: 10px;
        }

                /* BUTTON LOGIN: button utama */
        .btn-kai-dark {
            background: #2a2f5b; /* warna button */
            border: none; /* tiada border */
            color: #fff; /* warna teks */
            height: 48px; /* tinggi button */
            font-weight: 500; /* ketebalan font */
        }

        #closeModal:hover {
            color: #ccc;
        }

    </style>
</head>

<body>

<!-- ===== WRAPPER START ===== -->
<div class="wrapper">

    <!-- ===== SIDEBAR ===== -->
    <?php include "../includes/sidebar.php"; ?>

    <!-- ===== MAIN PANEL ===== -->
    <div class="main-panel">

        <!-- ===== TOPBAR ===== -->
        <?php include "../includes/topbar.php"; ?>

        <!-- ===== CONTENT ===== -->
        <div class="container">
            <div class="page-inner">

                <!-- ===== TITLE ===== -->
                <div class="page-header">
                    <h3 class="fw-bold mb-3">View Profile</h3>
                </div>

                <!-- ===== CENTER ===== -->
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-8">

                        <div class="profile-container">
                            <div class="profile-card">

                                <!-- ===== HEADER PROFILE ===== -->
                                <div class="profile-header">

                                    <?php
                                    $imgPath = $_SERVER['DOCUMENT_ROOT'] . "/kaiadmin-lite-1.2.0/assets/img/profile/" . $user_id . ".jpg";
                                    ?>

                                    <img 
                                        id="profileImg"
                                        src="/kaiadmin-lite-1.2.0/assets/img/profile/<?= $user_id ?>.jpg?v=<?= file_exists($imgPath) ? filemtime($imgPath) : time(); ?>"
                                        onerror="this.src='/kaiadmin-lite-1.2.0/assets/img/profile/default.png';"
                                        class="profile-avatar"
                                    >

                                    <div class="profile-name">
                                        <?= htmlspecialchars($profile['user_fullname'] ?? '-') ?>
                                    </div>

                                    <div class="profile-role">
                                        <?= htmlspecialchars($profile['role_name'] ?? '-') ?>
                                    </div>

                                </div>
                                <!-- ===== END HEADER ===== -->


                                <!-- ===== PERSONAL ===== -->
                                <div class="section-title">Personal Information</div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Full Name</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['user_fullname'] ?? '-') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Phone</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['user_mobile_no'] ?? '-') ?></div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">Username</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['user_name'] ?? '-') ?></div>
                                    </div>

                                </div>


                                <!-- ===== ACCOUNT ===== -->
                                <div class="section-title">Account Information</div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Email</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['user_email'] ?? '-') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Role</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['role_name'] ?? '-') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Division</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['division_name'] ?? '-') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Unit</div>
                                        <div class="info-value"><?= htmlspecialchars($profile['unit_name'] ?? '-') ?></div>
                                    </div>
                                </div>


                                <!-- ===== ACTIVITY ===== -->
                                <div class="section-title">Activity</div>

                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">Last Login</div>
                                        <div class="info-value">
                                            <?= !empty($profile['last_login']) 
                                                ? date('d M Y H:i', strtotime($profile['last_login'])) 
                                                : 'Never'; ?>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Created</div>
                                        <div class="info-value">
                                            <?= !empty($profile['created_at']) 
                                                ? date('d M Y H:i', strtotime($profile['created_at'])) 
                                                : '-'; ?>
                                        </div>
                                    </div>

                                    <div class="info-item">
                                        <div class="info-label">Last Updated</div>
                                        <div class="info-value">
                                            <?= !empty($profile['updated_at']) 
                                                ? date('d M Y H:i', strtotime($profile['updated_at'])) 
                                                : '-'; ?>
                                        </div>
                                    </div>
                                </div>


                                <!-- ===== BUTTON ===== -->
                                <a href="my-profile.php" class="btn btn-kai-dark mt-4 w-100">
                                    Edit Profile
                                </a>

                            </div> <!-- END profile-card -->
                        </div> <!-- END profile-container -->

                    </div>
                </div>

            </div>
        </div>
        <!-- ===== END CONTENT ===== -->

    </div> <!-- END main-panel -->

</div> <!-- END wrapper -->


<!-- ===== MODAL IMAGE ===== -->
<div id="imgModal" class="image-modal">
    
    <!-- TOMBOL CLOSE -->
    <span id="closeModal" style="
        position:fixed;
        top:20px;
        right:30px;
        font-size:40px;
        color:white;
        cursor:pointer;
        z-index:2000;
    ">&times;</span>

    <!-- GAMBAR -->
    <img id="modalImg">

</div>


<!-- ===== JS (WAJIB BIAR SIDEBAR HIDUP) ===== -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>


<!-- ===== CUSTOM JS ===== -->
<script>
const img = document.getElementById("profileImg");
const modal = document.getElementById("imgModal");
const modalImg = document.getElementById("modalImg");
const closeBtn = document.getElementById("closeModal");

// buka modal
img.onclick = function(){
    modal.style.display = "block";
    modalImg.src = this.src;
}

// klik background baru close
modal.addEventListener("click", function(e){
    if(e.target === modal){
        modal.style.display = "none";
    }
});

// tombol ESC
document.addEventListener("keydown", function(e){
    if(e.key === "Escape"){
        modal.style.display = "none";
    }
});

</script>

</body>
</html>