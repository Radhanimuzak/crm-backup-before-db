<?php
/* ================= ROLE ================= */
$role = $_SESSION['role'] ?? 0; 
// AMBIL role dari SESSION (hasil login)
// jika tiada → default 0
// FLOW: login → simpan session → guna di sini

/* ================= DATABASE ================= */
require "../config/database.php"; 
// SAMBUNG ke database
// ambil file database.php → hasilkan $conn
// FLOW: file ini → require → connect DB

/* ================= USER DATA ================= */
$user_id = $_SESSION['user_id']; 
// AMBIL user_id dari SESSION

$getUser = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id'"); 
// QUERY ambil data user dari table users
// FLOW: PHP → DB → ambil data ikut user_id

$user = mysqli_fetch_assoc($getUser); 
// tukar result query jadi array
// OUTPUT: $user['user_fullname'], $user['user_email']

/* ================= NOTIF (ONLY ADMIN) ================= */
$notifCount = 0; 
// default jumlah notif (elak error)

$notifQuery = null; 
// tempat simpan query notif

if($role == 1){
    // hanya ADMIN (role=1)

        $notifQuery = mysqli_query($conn, "
        SELECT user_id, user_name
        FROM users
        WHERE role_id IS NULL
        ORDER BY created_at DESC
        ");
    // ambil user belum aktif
    // FLOW: DB users → filter inactive → simpan dalam $notifQuery

    $notifCount = mysqli_num_rows($notifQuery);
    // kira jumlah data → digunakan untuk badge notif
}
// END PHP (logic selesai, data dihantar ke HTML bawah)
?>

<!-- ================= STYLE START ================= -->
<style>
.topbar-nav .dropdown-menu {
    right: 0 !important;        /* dropdown align kanan */
    left: auto !important;      /* buang align kiri */
    transform: none !important; /* reset posisi bootstrap */
}

.navbar-nav .dropdown-menu {
    position: absolute !important; /* pastikan dropdown floating */
}
</style>
<!-- ================= STYLE END ================= -->

<!-- ================= HEADER START ================= -->
<div class="main-header"> <!-- MULA HEADER -->

    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <!-- NAVBAR UTAMA -->

        <div class="container-fluid">
        <!-- WRAPPER FULL WIDTH -->

            <!-- ================= SEARCH START ================= -->
            <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            <!-- search hanya desktop -->

                <div class="input-group">

                    <div class="input-group-prepend">
                        <button type="submit" class="btn btn-search pe-1">
                            <i class="fa fa-search search-icon"></i>
                        </button>
                        <!-- butang search (belum connect backend) -->
                    </div>

                    <input type="text" placeholder="Search ..." class="form-control">
                    <!-- INPUT user → belum dihantar ke mana-mana -->
                </div>
            </nav>
            <!-- ================= SEARCH END ================= -->

            <!-- ================= TOPBAR START ================= -->
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
            <!-- container icon kanan -->

                <!-- ================= NOTIF START ================= -->
                <?php if($role == 1): ?>

                <li class="nav-item topbar-icon dropdown hidden-caret">

                    <a class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#">

                        <i class="fa fa-bell"></i>

                        <?php if ($notifCount > 0): ?>
                            <span class="notification">
                                <?= $notifCount; ?>
                            </span>
                        <?php endif; ?>

                    </a>

                    <ul class="dropdown-menu notif-box animated fadeIn">

                        <li>
                            <div class="dropdown-title">
                                You have <?= $notifCount; ?> new messages
                            </div>
                        </li>

                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">

                                <?php if($notifQuery && mysqli_num_rows($notifQuery) > 0): ?>

                                    <?php while($n = mysqli_fetch_assoc($notifQuery)): ?>

                                    <a href="/kaiadmin-lite-1.2.0/profile/inbox.php">

                                        <div class="notif-icon notif-primary">
                                            <i class="fa fa-user-plus"></i>
                                        </div>

                                        <div class="notif-content">
                                            <span class="block">
                                                <?= htmlspecialchars($n['user_name']); ?> registered
                                            </span>

                                            <span class="time">
                                                Open Inbox
                                            </span>
                                        </div>

                                    </a>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <p class="text-center p-3">
                                        No new messages
                                    </p>

                                <?php endif; ?>

                                </div>
                            </div>
                        </li>

                        <li>
                            <a class="see-all"
                            href="/kaiadmin-lite-1.2.0/profile/inbox.php">
                            View All Inbox
                            <i class="fa fa-angle-right"></i>
                            </a>
                        </li>

                    </ul>

                </li>

                <?php endif; ?>
                <!-- ================= NOTIF END ================= -->

                <!-- ================= PROFILE START ================= -->
                <li class="nav-item topbar-user dropdown hidden-caret">

                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">

                        <div class="avatar-sm">
                            <img 
                                src="../assets/img/profile/<?= $user_id ?>.jpg?v=<?= time(); ?>" 
                                onerror="this.src='../assets/img/profile/default.png'"
                                class="avatar-img rounded-circle">
                            <!-- ambil gambar dari folder profile ikut user_id -->
                            <!-- jika tiada → guna default.png -->
                        </div>

                        <span class="profile-username">
                            <span class="op-7">Hi,</span>

                            <span class="fw-bold">
                                <?= htmlspecialchars($user['user_name']); ?>
                                <!-- ambil nama dari DB -->
                            </span>
                        </span>

                    </a>

                    <!-- ================= DROPDOWN START ================= -->
                    <ul class="dropdown-menu dropdown-user animated fadeIn">

                        <div class="dropdown-user-scroll scrollbar-outer">

                            <!-- PROFILE BOX START -->
                            <li>
                                <div class="user-box">

                                    <div class="avatar-lg">
                                        <img 
                                            src="../assets/img/profile/<?= $user_id ?>.jpg?v=<?= time(); ?>" 
                                            onerror="this.src='../assets/img/profile/default.png'"
                                            class="avatar-img rounded">
                                    </div>

                                    <div class="u-text">
                                        <h4><?= htmlspecialchars($user['user_fullname']); ?></h4>

                                        <p class="text-muted" style="word-break: break-all;">
                                            <?= htmlspecialchars($user['user_email']); ?>
                                        </p>

                                        <a href="/kaiadmin-lite-1.2.0/profile/view-profile.php"
                                           class="btn btn-xs btn-secondary btn-sm">
                                            View Profile
                                        </a>
                                        <!-- klik → pergi view-profile.php -->
                                    </div>

                                </div>
                            </li>
                            <!-- PROFILE BOX END -->

                            <!-- MENU START -->
                            <li>

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/kaiadmin-lite-1.2.0/profile/my-profile.php">
                                    My Profile
                                </a>
                                <!-- klik → pergi my-profile.php -->

                                <!-- <a class="dropdown-item" href="#">
                                    My Balance
                                </a>
                                belum connect DB -->

                                <a class="dropdown-item" href="/kaiadmin-lite-1.2.0/profile/inbox.php">
                                    Inbox
                                </a>
                                <!-- belum ada backend -->

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item" href="/kaiadmin-lite-1.2.0/profile/account-setting.php">
                                    Account Setting
                                </a>
                                <!-- belum link sebenar -->

                                <div class="dropdown-divider"></div>

                                <a class="dropdown-item text-danger" href="../auth/logout.php">
                                    Logout
                                </a>
                                <!-- FLOW:
                                     klik → logout.php
                                     → session_destroy()
                                     → redirect login -->

                            </li>
                            <!-- MENU END -->

                        </div>

                    </ul>
                    <!-- ================= DROPDOWN END ================= -->

                </li>
                <!-- ================= PROFILE END ================= -->

            </ul>
            <!-- ================= TOPBAR END ================= -->

        </div>
        <!-- END CONTAINER -->

    </nav>
    <!-- END NAVBAR -->

</div>
<!-- ================= HEADER END ================= -->