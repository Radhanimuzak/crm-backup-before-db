<?php
session_start(); 
/* MULA SISTEM → aktifkan session user
   Ambil: data session dari browser (login sebelum ini)
   Kegunaan: simpan maklumat user seperti role_id
   Aliran: Login → session disimpan → digunakan di page ini */

/* =========================
   AUTH (LOGIN + ROLE)
========================= */
$required_role = 2; 
/* Tetapkan role diperlukan = 2 (Manager)
   Tujuan: hanya Manager boleh akses page ini */

require "../config/auth-check.php"; 
/* Ambil dari: ../config/auth-check.php
   Fungsi:
   - Semak session user wujud atau tidak
   - Banding role user dengan $required_role
   Aliran:
   session → auth-check
   jika TAK LULUS → redirect keluar (login / no access)
   jika LULUS → teruskan ke page ini */

/* =========================
   DATABASE
========================= */
require "../config/database.php"; 
/* Ambil dari: ../config/database.php
   Fungsi:
   - Sambung ke database
   - Sediakan connection ($conn / $pdo)
   Aliran:
   Page → database → sedia untuk query
   Nota: Dalam page ini belum digunakan */
?>

<!DOCTYPE html>
<html lang="en"> <!-- MULA HTML -->

<head>

    <!-- ================= META ================= -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" /> <!-- Keserasian browser -->
    <title>Manager Dashboard</title> <!-- Tajuk tab browser -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive -->

    <!-- ================= FONT ================= -->
    <script src="/kaiadmin-lite-1.2.0/assets/js/plugin/webfont/webfont.min.js"></script>
    <!-- Ambil: folder assets template KaiAdmin
         Fungsi: load font Google + icon -->

<script>
    WebFont.load({ 
        /* MULA: panggil fungsi WebFont untuk muatkan font */

        google: { 
            families: ["Public Sans:300,400,500,600,700"] 
        }, 
        /* Sumber: Google Fonts
           Input: nama font + ketebalan (300–700)
           Output: font digunakan dalam paparan dashboard
           Aliran:
           Browser → Google Fonts → muat turun font → digunakan dalam CSS */

        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons"
            ],
            urls: ["/kaiadmin-lite-1.2.0/assets/css/fonts.min.css"]
        }, 
        /* Sumber: fail tempatan (local project)
           Lokasi: /assets/css/fonts.min.css
           Fungsi:
           - muatkan icon (font awesome & simple icons)
           - digunakan pada sidebar, button dan UI lain
           Aliran:
           Browser → fail CSS local → muat font icon → digunakan dalam HTML */

        active: function () {
            sessionStorage.fonts = true;
        } 
        /* Akan dijalankan selepas semua font selesai dimuat
           Simpan ke: sessionStorage (browser)
           Tujuan:
           - tanda font sudah dimuat
           - elak muat berulang (lebih laju)
           Aliran:
           Font siap → fungsi active jalan → simpan status */

    }); 
    /* TAMAT WebFont.load → semua font sedia digunakan */
</script>

    <!-- ================= CSS ================= -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css"> <!-- Layout UI -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css"> <!-- Plugin -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css"> <!-- Style utama -->

</head> <!-- TAMAT HEAD -->

<body> <!-- MULA BODY -->

<div class="wrapper"> <!-- MULA WRAPPER (container utama semua layout) -->

    <!-- SIDEBAR -->
    <?php include "../includes/sidebar.php"; ?>
    <!-- Ambil: ../includes/sidebar.php
         Fungsi: paparkan menu kiri ikut role user
         Aliran: Page → include → render sidebar -->

    <div class="main-panel"> <!-- MULA PANEL UTAMA (content kanan) -->

        <!-- TOPBAR -->
        <?php include "../includes/topbar.php"; ?>
        <!-- Ambil: ../includes/topbar.php
             Fungsi: navbar atas (user info, logout, dll)
             Aliran: Page → include → render topbar -->

        <!-- ================= CONTENT ================= -->
        <div class="container"> <!-- MULA container content -->
            <div class="page-inner"> <!-- MULA pembungkus isi -->

                <div class="page-header"> <!-- MULA header page -->
                    <h4 class="page-title">Manager Dashboard</h4> <!-- Tajuk halaman -->

                    <ul class="breadcrumbs"> <!-- Navigasi breadcrumb (laluan halaman) -->

                        <li class="nav-home">
                            <i class="icon-home"></i>
                        </li> <!-- Ikon rumah → kembali ke halaman utama -->
                        <li class="separator">
                            <i class="icon-arrow-right"></i>
                        </li> <!-- Pemisah antara menu -->
                        <li class="nav-item">Manager</li> 
                        <!-- Paparan kategori: Manager (bahagian semasa) -->
                        <li class="separator">
                            <i class="icon-arrow-right"></i>
                        </li> <!-- Pemisah -->
                        <li class="nav-item">Dashboard</li> 
                        <!-- Halaman semasa yang sedang dibuka -->
                    </ul> <!-- TAMAT breadcrumb -->

                </div> <!-- TAMAT page-header -->

                <div class="card"> <!-- MULA card -->
                    <div class="card-body text-center"> <!-- MULA isi card -->

                        <h3>Welcome to CRM Manager</h3> <!-- Paparan teks -->
                        <p class="text-muted">
                            Manager dashboard will show staff reports and performance.
                        </p> <!-- Info fungsi page -->

                    </div> <!-- TAMAT card-body -->
                </div> <!-- TAMAT card -->

            </div> <!-- TAMAT page-inner -->
        </div> <!-- TAMAT container -->

        <!-- ================= FOOTER ================= -->
        <footer class="footer"> <!-- MULA footer -->
            <div class="container-fluid">
                CRM System Dashboard
            </div>
        </footer> <!-- TAMAT footer -->

    </div> <!-- TAMAT main-panel -->

</div> <!-- TAMAT wrapper -->

<!-- ================= JS ================= -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script> <!-- JS utama -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/popper.min.js"></script> <!-- Tooltip -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script> <!-- Bootstrap -->
<script src="/kaiadmin-lite-1.2.0/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script> <!-- Scroll -->
<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script> <!-- Script dashboard -->

</body> <!-- TAMAT BODY -->
</html> <!-- TAMAT HTML -->