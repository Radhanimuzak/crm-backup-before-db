<?php

/**
 * ==========================================================
 * FILE : dashboard.php (/admin/)
 * FUNGSI:
 * Papar dashboard admin selepas login
 * FLOW:
 * login → login-process → SESSION → dashboard → auth-check → paparan
 * INPUT:
 * - SESSION (user_id, role)
 * OUTPUT:
 * - Paparan dashboard ke browser
 * END:
 * User nampak dashboard (tiada proses lanjut)
 * =========================================================*/

/* ================= START ================= */
/**
 * Mula file
 * Ambil SESSION dari login-process (user_id, role) */
session_start();


/* ================= AUTH ================= */
/**
 * Tetapkan hanya admin (role = 1) */
$required_role = 1;

/**
 * Validasi:
 * - Ambil SESSION
 * - Semak login & role
 * FLOW:
 * dashboard.php → auth-check.php
 * RESULT:
 * Lulus → teruskan
 * Gagal → redirect keluar
 * END:
 * Jika gagal → berhenti
 * Jika lulus → sambung dashboard */
require "../config/auth-check.php";

/* ================= SECURITY ================= */
/* Elak cache browser
 * Tujuan: user tak boleh tekan "back" selepas logout */
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/* ================= DATABASE ================= */
/**
 * Sambung database
 * Ambil dari: ../config/database.php
 * Output: $conn (diguna untuk query) */
require "../config/database.php";

/* ================= PROCESS (NOTIF) ================= */
/**
 * Ambil data dari tabel users
 * Syarat: user_status = 'inactive'
 * FLOW:
 * database → query → $notifQuery
 * Tujuan: paparan notif di topbar */
$notifQuery = mysqli_query($conn, "
    SELECT user_id, user_name
    FROM users
    WHERE user_status='inactive'
    ORDER BY created_at DESC
");

/**
 * Kira jumlah data notif
 * Output: $notifCount (jumlah notif)
 *
 * END:
 * Data dihantar ke UI (topbar/dashboard) */
$notifCount = mysqli_num_rows($notifQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <!-- ================= META ================= -->
    <!-- Kompatibiliti browser -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Tajuk halaman (dipapar di tab browser) -->
    <title>CRM Admin Dashboard</title>

    <!-- Responsive untuk mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ================= FONT ================= -->
    <!-- Load font Google + custom icon -->
    <!-- FLOW: browser → ambil font → simpan dalam sessionStorage -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>

<script>
    /* PROSES LOAD FONT
     * SUMBER:
     * - Google Fonts (Public Sans)
     * - Custom font & icon (fonts.min.css)
     * FLOW:
     * browser → WebFont.load() → ambil font → apply ke UI
     * OUTPUT:
     * - Font & icon siap digunakan dalam dashboard */
    WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
            families: [
                "Font Awesome 5 Solid",
                "Font Awesome 5 Regular",
                "Font Awesome 5 Brands",
                "simple-line-icons"
            ],
            urls: ["../assets/css/fonts.min.css"]
        },
        active: function () {
            /* Simpan status font dalam sessionStorage
             * Tujuan: elak load ulang (lebih laju)
             * END:
             * Font sudah aktif → UI guna font ini */
            sessionStorage.fonts = true;
        }
    });
</script>


    <!-- ================= CSS ================= -->
    <!-- Styling utama UI -->
    <!-- FLOW: browser load CSS → apply ke semua element -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/plugins.min.css">
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css">
    <!-- END HEAD: semua setting UI siap, terus ke body -->


<!-- ================= CUSTOM STYLE ================= -->
<style>

    /* STYLE HEADER LOGO
     * Tujuan: susun posisi logo (tengah + jarak kiri)
     * OUTPUT: logo nampak kemas di header */
    .logo-header {
        height: 95px;
        display: flex;
        align-items: center;
        padding-left: 20px;
    }

    /* STYLE GAMBAR LOGO
     * Besarkan logo + anchor dari kiri */
    .logo-header .logo img {
        height: 48px;
        transform: scale(1.6);
        transform-origin: left center;
    }

    /* BACKGROUND SIDEBAR
     * Gradient gelap (atas → bawah) */
    .sidebar {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    }

    /* ITEM MENU SIDEBAR
     * Tambah ruang + bulatkan sudut */
    .sidebar .nav > .nav-item > a {
        border-radius: 8px;
        margin: 4px 8px;
    }

    /* MENU AKTIF
     * Highlight item yang sedang dipilih */
    .sidebar .nav > .nav-item.active > a {
        background: rgba(255, 255, 255, 0.08);
    }

    /* LOGO BESAR
     * Dipaparkan keadaan normal */
    .logo-full {
        height: 40px;
        transition: all 0.3s;
    }

    /* LOGO KECIL
     * Default: disembunyikan */
    .logo-mini {
        height: 28px;
        display: none;
    }

    /* SAAT SIDEBAR COLLAPSE
     * Sembunyikan logo besar */
    .sidebar_minimize .logo-full {
        display: none;
    }

    /**
     * SAAT SIDEBAR COLLAPSE
     * Papar logo kecil
     * FLOW:
     * user klik minimize → class sidebar_minimize aktif → style berubah
     * END:
     * UI sidebar jadi compact */
    .sidebar_minimize .logo-mini {
        display: block;
    }

</style>

<!-- END HEAD: semua style siap, seterusnya masuk body untuk paparan UI -->
</head>


<body>

<!-- ================= START UI ================= -->
<!-- Mula paparan UI dashboard -->
<div class="wrapper">


    <!-- ================= SIDEBAR ================= -->
    <!-- 
        FILE: ../includes/sidebar.php
        FUNGSI:
        Papar menu navigasi (dashboard, users, dll)

        FLOW:
        dashboard.php → include sidebar.php → paparan menu

        INPUT: tiada
        OUTPUT: UI sidebar
    -->
    <?php include "../includes/sidebar.php"; ?>


    <!-- ================= MAIN PANEL ================= -->
    <div class="main-panel">


        <!-- ================= TOPBAR ================= -->
        <?php
        /**
         * DATA KE TOPBAR:
         * - $notifCount (jumlah notif)
         * - $notifQuery (data user inactive)
         *
         * FLOW:
         * dashboard.php → topbar.php
         *
         * Tujuan: paparan notifikasi di topbar
         */

        $notifCount = $notifCount ?? 0;
        $notifQuery = $notifQuery ?? null;

        include "../includes/topbar.php";
        ?>


        <!-- ================= CONTENT ================= -->
        <!-- 
            Kawasan isi dashboard

            FLOW:
            data → diproses → dipapar di sini

            Contoh:
            chart, statistik, report
        -->
        <div class="container">

            <div class="page-inner">

                <h4 class="page-title">Admin Dashboard</h4>
                <!-- OUTPUT: tajuk dashboard -->

            </div>

        </div>


        <!-- ================= FOOTER ================= -->
        <!-- Paparan footer -->
        <footer class="footer">
            <div class="container-fluid">
                CRM System Admin Dashboard
            </div>
        </footer>


    </div>

</div>
<!-- ================= END UI ================= -->
<!-- END:
     Semua UI siap dipapar ke browser -->


<!-- ================= JS ================= -->
<!-- Load script untuk fungsi UI -->
<!-- FLOW:
     browser → load JS → aktifkan interaksi (menu, animasi, dll) -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>

<!-- END FINAL:
     Page siap sepenuhnya (UI + function aktif) -->

</body> <!--  END body -->
</html> <!--  END html -->