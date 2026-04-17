<?php
session_start(); // [START] Mula session → ambil data login (SESSION dari server)
require "../config/auth-check.php"; // [CHECK LOGIN] Semak user login → jika tak login akan redirect keluar
require "../config/database.php"; // [DATABASE] Sambung ke database → guna $conn

/* ================= USER ================= */
/* CHECK: pastikan user_id wujud */

$user_id = $_SESSION['user_id']; /* ambil user_id dari session */ 
/* ===============================
   AMBIL DATA USER + JOIN RELATION
   (division & unit)
=============================== */
/* ===============================
   AMBIL DATA USER + SEMUA RELATION
   (role + division + unit)
=============================== */
$query = mysqli_query($conn, "
    SELECT 
        u.*, 
        r.role_name,
        d.division_name,
        un.unit_name
    FROM users u
    LEFT JOIN roles r 
        ON u.role_id = r.role_id
    LEFT JOIN divisions d 
        ON u.division_id = d.division_id
    LEFT JOIN units un 
        ON u.unit_id = un.unit_id
    WHERE u.user_id = $user_id
");

/* CHECK: jika query gagal */
if(!$query){
    die(mysqli_error($conn));
}

$profile = mysqli_fetch_assoc($query);

/* VALIDASI: jika user tak jumpa */
if(!$profile){
    die("User tidak dijumpai dalam database");
}

/* ================= UPDATE ================= */
if(isset($_POST['update_profile'])){ // [TRIGGER] Jalan bila button submit ditekan (POST)

        $fullname = trim($_POST['user_fullname']); // [INPUT] ambil data dari form
        $phone    = trim($_POST['user_mobile_no']); // [INPUT] ambil data dari form
        $username = trim($_POST['user_name']); // [INPUT] ambil data dari form

        $stmt = mysqli_prepare($conn, "
            UPDATE users SET 
                user_fullname=?,
                user_name=?,
                user_mobile_no=?,
                updated_at = NOW()
            WHERE user_id=?
        ");

        mysqli_stmt_bind_param($stmt, "sssi", $fullname, $username, $phone, $user_id);

        $update = mysqli_stmt_execute($stmt);

        if(!$update){
            die("Error update: " . mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);

    /* ================= UPLOAD FOTO ================= */
    if(!empty($_FILES['user_image']['name'])){ // [CHECK] jika user upload gambar

        $folder = __DIR__ . "/../assets/img/profile/"; // [DESTINASI] lokasi simpan gambar → folder profile

        if(!is_dir($folder)){
            mkdir($folder, 0777, true); // [CREATE] buat folder jika belum ada
        }

        $tmp = $_FILES['user_image']['tmp_name']; // [INPUT FILE] file sementara dari upload
        $image_info = getimagesize($tmp); // [VALIDASI] semak file adalah gambar

        if($image_info === false){
            echo "<script>alert('File bukan gambar!'); window.location='my-profile.php';</script>";
            exit; // [END ERROR] hentikan proses jika bukan gambar
        }

        $mime = $image_info['mime']; // [TYPE] ambil jenis file (jpeg/png)

        if($mime == 'image/jpeg'){
            $image = imagecreatefromjpeg($tmp); // [PROSES] convert jpeg
        } elseif($mime == 'image/png'){
            $image = imagecreatefrompng($tmp); // [PROSES] convert png
        } else {
            echo "<script>alert('Format harus JPG atau PNG!'); window.location='my-profile.php';</script>";
            exit; // [END ERROR] stop jika format tak valid
        }

        $image_name = $user_id . ".jpg"; // [NAMING] nama file ikut user_id (overwrite lama)

        if(!imagejpeg($image, $folder . $image_name, 90)){
            echo "<script>alert('Gagal upload gambar!'); window.location='my-profile.php';</script>";
            exit; // [END ERROR] stop jika gagal simpan
        } // [OUTPUT] gambar disimpan ke folder profile/
    }

    echo "<script>alert('Profile berhasil diupdate!'); window.location='my-profile.php';</script>";
    exit; // [END UTAMA] selesai → redirect → hentikan script
}
?>

<!DOCTYPE html> <!-- [START FILE] Mula dokumen HTML, browser tahu ini HTML5 -->

<html lang="en"> <!-- Root HTML, bahasa English digunakan -->

<head>

    <meta charset="UTF-8">
    
    <title>My Profile</title>

    <?php include('../includes/header.php'); ?>

    <style>

        /* ===============================
        CONTAINER PROFILE
        Pusatkan layout & hadkan lebar
        =============================== */
        .profile-container {
            max-width: 900px; /* lebar maksimum supaya tak terlalu luas */
            margin: 0 auto;   /* center ke tengah */
        }

        /* ===============================
        CARD STYLE
        Background putih + shadow
        =============================== */
        .profile-card {
            background: #fff; /* background putih */
            border-radius: 12px; /* sudut bulat */
            padding: 30px; /* ruang dalam */
            box-shadow: 0 4px 12px rgba(0,0,0,0.05); /* bayang lembut */
        }

        /* ===============================
        HEADER PROFILE
        Avatar + nama + role
        =============================== */
        .profile-header {
            display: flex; /* guna flex supaya sejajar */
            align-items: center; /* align tengah vertical */
            gap: 20px; /* jarak antara avatar & text */
            margin-bottom: 30px; /* jarak bawah */
        }

        /* ===============================
        AVATAR
        =============================== */
        .profile-avatar {
            width: 80px; /* lebar avatar */
            height: 80px; /* tinggi avatar */
            border-radius: 50%; /* bulat */
            object-fit: cover; /* supaya gambar tak stretch */
            border: 3px solid #e5e7eb; /* border ringan */
        }

        /* ===============================
        NAMA USER
        =============================== */
        .profile-name {
            font-size: 20px; /* saiz text */
            font-weight: 600; /* bold */
        }

        /* ===============================
        ROLE BADGE
        =============================== */
        .profile-role {
            display: inline-block; /* <-- INI KUNCINYA */
            background: #e7f1ff;
            color: #0d6efd;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
        }

        /* ===============================
        space for text role supaya tak terlalu rapat dengan nama
        =============================== */
        .profile-role {
            display: inline-block;
            margin-top: 5px;
        }

        /* ===============================
        GRID FORM
        2 column layout
        =============================== */
        .form-grid {
            display: grid; /* guna grid */
            grid-template-columns: 1fr 1fr; /* 2 kolum */
            gap: 20px; /* jarak */
        }

        /* ===============================
        INPUT READONLY
        =============================== */
        .readonly {
            background: #f1f5f9; /* warna kelabu */
            cursor: not-allowed; /* cursor disabled */
        }

        /* ===============================
        TAJUK SECTION
        =============================== */
        .section-title {
            font-weight: 600; /* bold */
            margin: 25px 0 10px; /* jarak atas bawah */
        }

        /* ===============================
        BUTTON ACTION
        =============================== */
        .form-actions {
            display: flex; /* flex */
            justify-content: flex-end; /* align kanan */
            gap: 10px; /* jarak button */
            margin-top: 25px; /* jarak atas */
        }

        /* BUTTON EDIT */
        .btn-kai-dark {
            background: #2a2f5b; /* warna button */
            border: none; /* tiada border */
            color: #fff; /* warna teks */
            height: 48px; /* tinggi button */
            font-weight: 500; /* ketebalan font */
        }

    </style>
    <!-- [END STYLE] Tamat styling custom -->

</head>
<!-- [END HEAD] Tamat setting page -->

<body> <!-- [START BODY] Mula paparan UI (apa user nampak) -->

<div class="wrapper"> <!-- Wrapper utama semua layout -->

    <!-- SIDEBAR -->
    <?php include "../includes/sidebar.php"; ?> <!-- Ambil UI sidebar dari file: ../includes/sidebar.php (display sahaja, tiada data POST) -->

    <div class="main-panel"> <!-- Panel utama (content kanan) -->

        <!-- TOPBAR -->
        <?php include "../includes/topbar.php"; ?> <!-- Ambil UI topbar dari file: ../includes/topbar.php -->

        <div class="container"> <!-- Container penuh -->
            <div class="page-inner"> <!-- Inner spacing page -->

                <div class="page-header"> <!-- Header page -->
                    <h3 class="fw-bold mb-3">My Profile</h3> <!-- Tajuk page -->
                </div>

                        <!-- ===============================
                            CENTER CONTENT
                        =============================== -->
                        <div class="row justify-content-center">
                            <div class="col-md-10 col-lg-8">


                            <!-- ===============================
                                PROFILE CONTAINER (CENTER)
                            =============================== -->
                            <div class="profile-container">

                            <div class="profile-card">

                                <!-- ===============================
                                    HEADER PROFILE
                                =============================== -->
                                <div class="profile-header">

                                    <!-- GAMBAR PROFILE -->
                                    <img id="previewImage"
                                        src="/kaiadmin-lite-1.2.0/assets/img/profile/<?= $user_id ?>.jpg?v=<?= time(); ?>" 
                                        onerror="this.src='/kaiadmin-lite-1.2.0/assets/img/profile/default.png';"
                                        class="profile-avatar">

                                    <!-- NAMA & ROLE -->
                                    <div>
                                        <div class="profile-name">
                                            <?= htmlspecialchars($profile['user_fullname']); ?>
                                        </div>

                                        <div class="profile-role">
                                            <?= htmlspecialchars($profile['role_name'] ?? 'Tiada Role'); ?> <!-- papar role dari table roles -->
                                        </div>
                                    </div>

                                </div>

                                <!-- ===============================
                                    FORM UPDATE PROFILE
                                =============================== -->
                                <form method="POST" enctype="multipart/form-data">

                                    <!-- ===============================
                                        PERSONAL INFO
                                    =============================== -->
                                    <div class="section-title">Personal Information</div>

                                    <div class="form-grid">

                                        <!-- NAMA PENUH -->
                                        <div>
                                            <label>Full Name</label>
                                            <input type="text"
                                                name="user_fullname"
                                                class="form-control"
                                                value="<?= htmlspecialchars($profile['user_fullname']); ?>">
                                        </div>

                                        <!-- TELEFON -->
                                        <div>
                                            <label>Phone</label>
                                            <input type="text"
                                                name="user_mobile_no"
                                                class="form-control"
                                                value="<?= htmlspecialchars($profile['user_mobile_no']); ?>">
                                        </div>
                                          
                                         <div>
                                            <label>Username</label>
                                            <input type="text"
                                                name="user_name"
                                                class="form-control"
                                                value="<?= htmlspecialchars($profile['user_name']); ?>">
                                        </div>

                                    </div>

<!-- kalau nak letak information last login, unit, division, role bawah ni  -->

                                    <!-- ===============================
                                        UPLOAD PHOTO
                                    =============================== -->
                                    <div class="section-title">Profile picture</div>

                                    <input type="file"
                                        name="user_image"
                                        class="form-control mb-3"
                                        accept="image/jpeg, image/png"
                                        onchange="previewFile(this)">

                                    <!-- ===============================
                                        BUTTON ACTION
                                    =============================== -->
                                    <div class="form-actions">

                                        <!-- BUTTON CANCEL -->
                                        <button type="button" class="btn btn-light" onclick="window.location.reload()">
                                            Cancel
                                        </button>

                                        <!-- BUTTON SAVE -->
                                        <button type="submit"
                                                name="update_profile"
                                                class="btn btn-kai-dark">
                                            Save Changes
                                        </button>

                                    </div>

                                </form>

                            </div>
                            </div>

                            </div> <!-- END card-body -->

                    </div> <!-- END col -->
                </div> <!-- END row -->

            </div> <!-- END page-inner -->
        </div> <!-- END container -->

    </div> <!-- END main-panel -->

</div> <!-- END wrapper -->

<script src="../assets/js/core/jquery-3.7.1.min.js"></script> 
<!-- LOAD jQuery -->
<!-- AMBIL dari: ../assets/js/core/ -->
<!-- FUNGSI: library asas untuk manipulasi DOM & event -->

<script src="../assets/js/core/popper.min.js"></script> 
<!-- LOAD Popper -->
<!-- FUNGSI: handle position tooltip / dropdown -->

<script src="../assets/js/core/bootstrap.min.js"></script> 
<!-- LOAD Bootstrap JS -->
<!-- FUNGSI: aktifkan component Bootstrap (modal, dropdown, dll) -->

<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script> 
<!-- LOAD plugin scrollbar -->
<!-- FUNGSI: custom scrollbar UI -->

<script src="../assets/js/kaiadmin.min.js"></script> 
<!-- LOAD template KaiAdmin -->
<!-- FUNGSI: control UI template (sidebar, layout, animation) -->


<script>
/* =========================
   FUNCTION: PREVIEW GAMBAR
   DIPANGGIL DARI:
   onchange="previewFile(this)"
   (input file dalam form)
========================= */
function previewFile(input){
    let file = input.files[0]; 
    /* AMBIL FILE pertama dari input
       SUMBER: user upload */

    if(file){
        let reader = new FileReader(); 
        /* OBJECT untuk baca file di browser */

        reader.onload = function(e){
            document.getElementById("previewImage").src = e.target.result;
            /* OUTPUT:
               Tukar src gambar preview
               TARGET: <img id="previewImage">
               DATA: base64 image */
        }

        reader.readAsDataURL(file); 
        /* PROSES:
           convert file → base64 URL
           supaya boleh preview tanpa upload ke server */
    }
}
</script> <!-- END SCRIPT -->

</body> <!-- [END BODY] Tamat paparan semua UI & script -->

</html> <!-- [END HTML] Tamat keseluruhan dokumen -->