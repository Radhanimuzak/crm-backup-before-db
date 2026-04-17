<?php
session_start();

/* ======================================
   LOGIN CHECK
====================================== */
require "../config/auth-check.php";
require "../config/database.php";

/* ======================================
   ADMIN ONLY
====================================== */
if($_SESSION['role'] != 1){
    header("Location: ../auth/login.php");
    exit();
}

/* ======================================
   GET PENDING REGISTER USER
====================================== */
$query = mysqli_query($conn,"
SELECT 
    user_id,
    user_name,
    user_fullname,
    user_email,
    created_at
FROM users
WHERE user_status='inactive'
AND role_id IS NULL
ORDER BY created_at DESC
");

$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<title>Inbox</title>

<?php include('../includes/header.php'); ?>

<style>

/* ===============================
PAGE
=============================== */
.inbox-wrapper{
    max-width: 980px;
    margin: 0 auto;
}

/* ===============================
CARD
=============================== */
.inbox-card{
    background:#ffffff;
    border-radius:14px;
    padding:25px;
    box-shadow:0 4px 14px rgba(0,0,0,0.05);
}

/* ===============================
MAIL ROW
=============================== */
.mail-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    padding:18px 16px;
    border-bottom:1px solid #eef1f6;
    text-decoration:none;
    color:#222;
    transition:0.2s ease;
}

.mail-row:hover{
    background:#f8f9fc;
    color:#111;
}

/* ===============================
LEFT SIDE
=============================== */
.mail-left{
    display:flex;
    gap:15px;
    align-items:flex-start;
}

/* ICON */
.mail-icon{
    width:48px;
    height:48px;
    min-width:48px;
    border-radius:50%;
    background:#e8f0ff;
    color:#2a2f5b;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:18px;
}

/* CONTENT */
.mail-subject{
    font-weight:700;
    font-size:15px;
    margin-bottom:4px;
}

.mail-preview{
    color:#6c757d;
    font-size:14px;
    line-height:1.5;
}

.mail-time{
    font-size:12px;
    color:#999;
    white-space:nowrap;
    text-align:right;
}

/* unread dot */
.unread-dot{
    width:10px;
    height:10px;
    background:#0d6efd;
    border-radius:50%;
    margin-top:6px;
}

/* EMPTY */
.empty-box{
    text-align:center;
    padding:60px 20px;
    color:#888;
}

.page-desc{
    color:#6c757d;
    font-size:14px;
}

</style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <?php include "../includes/sidebar.php"; ?>

    <div class="main-panel">

        <!-- TOPBAR -->
        <?php include "../includes/topbar.php"; ?>

        <div class="container">
            <div class="page-inner">

                <!-- PAGE TITLE -->
                <div class="page-header mb-4">
                    <h3 class="fw-bold mb-1">Inbox</h3>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-10">

                        <div class="inbox-wrapper">

                            <div class="inbox-card">

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="mb-0">
                                        Messages (<?= $total; ?>)
                                    </h4>
                                </div>

                                <?php if($total > 0): ?>

                                    <?php while($row = mysqli_fetch_assoc($query)): ?>

                                    <!-- FULL ROW CLICKABLE -->
                                    <a class="mail-row"
                                       href="../admin/user-role-assignment.php?user_id=<?= $row['user_id']; ?>">

                                        <!-- LEFT -->
                                        <div class="mail-left">

                                            <div class="unread-dot"></div>

                                            <div class="mail-icon">
                                                <i class="fa fa-user-plus"></i>
                                            </div>

                                            <div>

                                                <div class="mail-subject">
                                                    New User Registration
                                                </div>

                                                <div class="mail-preview">
                                                    <strong><?= htmlspecialchars($row['user_fullname']); ?></strong>
                                                    has registered and is waiting for role assignment.
                                                </div>

                                            </div>

                                        </div>

                                        <!-- RIGHT -->
                                        <div class="mail-time">
                                            <?= date("d M Y", strtotime($row['created_at'])); ?>
                                            <br>
                                            <?= date("h:i A", strtotime($row['created_at'])); ?>
                                        </div>

                                    </a>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <div class="empty-box">
                                        <i class="fa fa-inbox fa-2x mb-3"></i>
                                        <br>
                                        No new messages
                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- JS -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>

</body>
</html>