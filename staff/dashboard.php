<?php
session_start(); 
/* MULA PROGRAM
   Fungsi:
   - Mulakan session PHP
   - Digunakan untuk simpan data user (login, role, dll)
   Input:
   - Data session sedia ada (jika ada)
   Output:
   - Session aktif untuk digunakan sepanjang sistem
   Flow:
   Browser → PHP → Session server disambung */


/* =========================
   ROLE CHECK
========================= */
$required_role = 3; 
/* Tetapkan role yang diperlukan
   Fungsi:
   - Tentukan hanya user dengan role = 3 boleh akses page ini
   Input:
   - Nilai tetap (3)
   Output:
   - Digunakan oleh auth-check.php untuk validasi */

require "../config/auth-check.php"; 
/* Panggil fail auth-check.php
   Lokasi:
   - ../config/auth-check.php
   Fungsi:
   - Semak sama ada user sudah login
   - Semak sama ada role user sama dengan $required_role
   Input:
   - Data session (contoh: user_id, role)
   - $required_role (3)
   Proses:
   - Jika tidak login → redirect ke login page
   - Jika role tidak sama → block / redirect
   Output:
   - User dibenarkan akses page ini jika lulus
   Flow:
   Session → auth-check.php → validasi → (lulus/gagal) */

/* =========================
   DATABASE
========================= */
require "../config/database.php"; 
/* Panggil sambungan database
   Lokasi:
   - ../config/database.php
   Fungsi:
   - Sambungkan sistem ke database (MySQL / lain)
   Input:
   - Config database (host, username, password, db name)
   Proses:
   - Create connection (mysqli / PDO)
   Output:
   - Connection database (contoh: $conn)
   Flow:
   PHP file ini → database.php → sambung DB → boleh query data */
?>

<!DOCTYPE html>
<html lang="en">
<!-- MULA HTML
     Fungsi:
     - Struktur asas halaman web (dashboard staff)
     Flow:
     Browser → load HTML → render UI -->

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Pastikan compatibility dengan browser lama -->

    <title>Staff Dashboard</title>
    <!-- Tajuk tab browser -->

    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <!-- Responsive untuk semua device -->

    <!-- FONT ICON -->
    <script src="/kaiadmin-lite-1.2.0/assets/js/plugin/webfont/webfont.min.js"></script>
    <!-- Ambil library WebFont dari folder assets -->

    <script>
        WebFont.load({
            google: { families: ["Public Sans:300,400,500,600,700"] },
            /* Ambil font dari Google */

            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],
                urls: ["/kaiadmin-lite-1.2.0/assets/css/fonts.min.css"]
                /* Ambil icon dari file local (fonts.min.css) */
            },

            active: function () {
                sessionStorage.fonts = true;
            }
            /* Simpan status font dalam session browser */
        });
    </script>

    <!-- CSS -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css"/>
    <!-- Framework UI (layout, grid, button) -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css"/>
    <!-- Plugin tambahan (scroll, animation, dll) -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css"/>
    <!-- Style utama dashboard -->
</head>

<body>
<!-- MULA BODY
     Fungsi:
     - Paparkan semua UI kepada user -->

<div class="wrapper">
    <!-- Wrapper utama seluruh layout -->

    <!-- ================= SIDEBAR ================= -->
    <?php include "../includes/sidebar.php"; ?>
    <!-- Ambil sidebar dari:
         ../includes/sidebar.php
         Fungsi:
         - Menu navigasi (dashboard, user, dll)
         Flow:
         Page ini → include sidebar → paparkan menu -->

    <div class="main-panel">
        <!-- Panel utama content -->

        <!-- ================= TOPBAR ================= -->
        <?php include "../includes/topbar.php"; ?>
        <!-- Ambil topbar dari:
             ../includes/topbar.php
             Fungsi:
             - Header (profile, logout, notification)
             Flow:
             Page → include topbar → paparan header -->

        <!-- ================= CONTENT ================= -->
        <div class="container">
            <!-- Container content utama -->

            <div class="page-inner">
                <!-- Inner layout untuk spacing -->

                <h4 class="page-title">Staff Dashboard</h4>
                <!-- Tajuk halaman -->

                <div class="card">
                    <!-- Card UI -->

                    <div class="card-body text-center">
                        <!-- Isi card -->

                        <h3>Welcome to CRM Staff</h3>
                        <!-- Tajuk content -->

                        <p class="text-muted">
                            Staff dashboard will display assigned tasks and customers.
                        </p>
                        <!-- Penerangan fungsi dashboard -->

                    </div>
                </div>

            </div>
        </div>

        <!-- ================= FOOTER ================= -->
        <footer class="footer">
            <div class="container-fluid">
                CRM System Dashboard
            </div>
        </footer>
        <!-- Footer bawah page -->

    </div>

</div>

<!-- ================= JS ================= -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<!-- Library utama (jQuery) -->

<script src="/kaiadmin-lite-1.2.0/assets/js/core/popper.min.js"></script>
<!-- Untuk tooltip / dropdown -->

<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
<!-- Function Bootstrap (modal, button, dll) -->

<script src="/kaiadmin-lite-1.2.0/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<!-- Custom scrollbar -->

<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>
<!-- Script utama dashboard -->

</body>
</html>
<!-- TAMAT HTML
     END:
     - Semua UI siap render
     - JS aktifkan interaksi-->