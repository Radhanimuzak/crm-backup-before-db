<?php
$role = $_SESSION['role'] ?? 0;
/* INPUT: ambil role user dari SESSION (datang dari proses login & database) */

/* START: tentukan arah folder ikut role */

if ($role == 1) {
    $base = "/kaiadmin-lite-1.2.0/admin/"; 
    /* admin → semua link pergi folder /admin/ */

} elseif ($role == 2) {
    $base = "/kaiadmin-lite-1.2.0/manager/"; 
    /* manager → semua link pergi folder /manager/ */

} else {
    $base = "/kaiadmin-lite-1.2.0/staff/"; 
    /* selain itu (staff) → pergi folder /staff/ */
}

/* FLOW RINGKAS:
   database → login → simpan SESSION
   → ambil role_id
   → tentukan $base
   → digunakan pada link menu (contoh: $base/dashboard.php)*/

/* END: hasil akhir = $base (arah folder ikut role) */
?>

<style>
.logo-header {
    height: 95px; /* tinggi container logo */
    display: flex; /* guna flex untuk susun elemen */
    align-items: center; /* align tengah secara vertikal */
    padding-left: 20px; /* jarak dari kiri */
}

.logo-header .logo img {
    height: 48px; /* saiz asal logo */
    transform: scale(1.6); /* besarkan logo */
    transform-origin: left center; /* pembesaran dari kiri */
}

.sidebar {
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    /* background sidebar warna gelap gradient */
}

.sidebar .nav > .nav-item > a {
    border-radius: 8px; /* bucu menu bulat */
    margin: 4px 8px; /* jarak antara menu */
}

.sidebar .nav > .nav-item.active > a {
    background: rgba(255,255,255,0.08);
    /* warna menu aktif */
}
</style>

<div class="sidebar" data-background-color="dark">
    <!-- START SIDEBAR: container utama menu kiri -->

    <div class="sidebar-logo">
        <!-- section logo + toggle -->

        <div class="logo-header" data-background-color="dark">

            <a href="<?= $base ?>dashboard.php" class="logo">
                <!-- INPUT: $base (ditentukan dari role user dalam SESSION)
                     PROSES: gabung $base + dashboard.php
                     OUTPUT: redirect ke dashboard ikut role
                     contoh:
                     admin → /admin/dashboard.php
                     manager → /manager/dashboard.php
                     staff → /staff/dashboard.php -->

                <img src="/kaiadmin-lite-1.2.0/assets/img/logo_crm.png">
                <!-- sumber: file static dalam folder assets (bukan database) -->
            </a>

            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                    <!-- fungsi: buka/tutup sidebar
                         dikawal oleh JS template Kaiadmin -->
                </button>
            </div>

        </div>
    </div>
    <!-- END SIDEBAR HEADER:
         hasil akhir = logo + button toggle + link ke dashboard ikut role -->

    <!-- SECTION: MENU NAVIGATION -->
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <!-- div: wrapper sidebar + enable scroll -->

    <div class="sidebar-content">
        <!-- div: isi utama sidebar -->

        <ul class="nav nav-secondary">
            <!-- ul: list utama menu sidebar -->

            <!-- MENU: DASHBOARD -->
            <li class="nav-item">
                <!-- li: item menu -->
                <a href="<?= $base ?>dashboard.php">
                    <!-- a: link menu
                         INPUT: $base (dari SESSION role)
                         OUTPUT: pergi dashboard ikut role -->

                    <i class="fas fa-home"></i>
                    <!-- icon menu -->

                    <p>Dashboard</p>
                    <!-- label menu -->
                </a>
            </li>

            <!-- MENU: USER MANAGEMENT -->
            <?php if ($role == 1 || $role == 2): ?>
            <!-- LOGIC: hanya admin & manager dipaparkan -->

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#userMenu">
                    <!-- a: trigger dropdown -->

                    <i class="fas fa-user"></i>
                    <!-- icon -->

                    <p>User Management</p>
                    <!-- label -->

                    <span class="caret"></span>
                    <!-- span: icon arrow dropdown -->
                </a>

                <div class="collapse" id="userMenu">
                    <!-- div: container dropdown (collapse) -->

                    <ul class="nav nav-collapse">
                        <!-- ul: sub menu -->

                        <li>
                            <a href="<?= $base ?>users.php">
                                <!-- FLOW:
                                     klik → users.php
                                     → query database (users)
                                     → papar list -->

                                <span class="sub-item">User</span>
                                <!-- span: nama sub menu -->
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <!-- belum sambung file -->
                                <span class="sub-item">Division</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Unit</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Roles</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Role Permissions</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
            <?php endif; ?>

            <!-- MENU: SALES MANAGEMENT -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#salesMenu">
                    <!-- a: buka dropdown -->

                    <i class="fas fa-chart-line"></i>
                    <!-- icon -->

                    <p>Sales Management</p>
                    <!-- label -->

                    <span class="caret"></span>
                    <!-- arrow -->
                </a>

                <div class="collapse" id="salesMenu">
                    <!-- div dropdown -->

                    <ul class="nav nav-collapse">
                        <!-- ul sub menu -->

                        <li>
                            <a href="<?= $base ?>product.php">
                                <!-- FLOW:
                                     klik → product.php
                                     → ambil data produk dari database -->

                                <span class="sub-item">Product</span>
                            </a>
                        </li>

                        <li>
                            <a href="<?= $base ?>lead.php">
                                <!-- FLOW:
                                     klik → lead.php
                                     → ambil data lead dari database -->

                                <span class="sub-item">Lead</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Tasks</span>
                                <!-- belum sambung -->
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Opportunity</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Quote</span>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                <span class="sub-item">Customer</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <!-- MENU: CUSTOMER SUPPORT -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#supportMenu">
                    <!-- trigger dropdown -->

                    <i class="fas fa-headset"></i>
                    <p>Customer Support</p>
                    <span class="caret"></span>
                </a>

                <div class="collapse" id="supportMenu">
                    <ul class="nav nav-collapse">

                        <li>
                            <a href="#">
                                <!-- FLOW:
                                     nanti → work_order.php
                                     → ambil data ticket support -->

                                <span class="sub-item">Work Order</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

            <!-- MENU: AUDIT TRAIL -->
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#auditMenu">
                    <!-- trigger dropdown -->

                    <i class="fas fa-search"></i>
                    <p>Audit Trail</p>
                    <span class="caret"></span>
                </a>

                <div class="collapse" id="auditMenu">
                    <ul class="nav nav-collapse">

                        <li>
                            <a href="#">
                                <!-- FLOW:
                                     nanti → audit.php
                                     → ambil log aktiviti user -->

                                <span class="sub-item">Audit Trail</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

        </ul>
        <!-- END ul: semua menu sidebar -->

    </div>
    <!-- END sidebar-content -->

</div>
<!-- END sidebar-wrapper -->

</div>
<!-- END SIDEBAR:
     guna $base → arah ikut role → buka file sistem -->