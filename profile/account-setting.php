<?php
session_start();
include("../config/database.php");
include("../config/auth-check.php");

$user_id = $_SESSION['user_id'];

if(isset($_SESSION['success'])){
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

if(isset($_POST['save_notification'])){

    $notify_new_user = isset($_POST['notify_new_user']) ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET notify_new_user=? WHERE user_id=?");
    $stmt->bind_param("ii", $notify_new_user, $user_id);
    $stmt->execute();

    $_SESSION['success'] = "Notification setting updated successfully.";
    header("Location: account-setting.php");
    exit;
}

if (isset($_POST['change_password'])) {

    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $check = $conn->prepare("SELECT user_password FROM users WHERE user_id=?");
    $check->bind_param("i", $user_id);
    $check->execute();

    $result   = $check->get_result();
    $userData = $result->fetch_assoc();

    if (!password_verify($current_password, $userData['user_password'])) {

        $error = "Current password is incorrect.";

    } elseif ($new_password != $confirm_password) {

        $error = "New password and confirm password do not match.";

    } else {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $update = $conn->prepare("UPDATE users SET user_password=? WHERE user_id=?");
        $update->bind_param("si", $hashed_password, $user_id);
        $update->execute();

        $_SESSION['success'] = "Password updated successfully.";
        header("Location: account-setting.php");
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| GET USER DATA
|--------------------------------------------------------------------------
*/
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$user   = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
<?php include("../includes/header.php"); ?>
</head>

<body>

<div class="wrapper">

    <!-- Sidebar -->
    <?php include("../includes/sidebar.php"); ?>

    <!-- Main Panel -->
    <div class="main-panel">

        <!-- Header / Topbar -->
        <?php include("../includes/topbar.php"); ?>

        <!-- Content -->
        <div class="container">
            <div class="page-inner">

                    <!-- Title -->
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">
                            Account Setting
                        </h3>
                    </div>

                    <!-- Alerts -->
                    <?php if(isset($success)) { ?>
                        <div class="alert alert-success auto-dismiss">
                            <?= $success; ?>
                        </div>
                    <?php } ?>

                    <?php if(isset($error)) { ?>
                        <div class="alert alert-danger auto-dismiss">
                            <?= $error; ?>
                        </div>
                    <?php } ?>

                    <!-- CHANGE PASSWORD CARD -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Change Password</div>
                            </div>

                            <div class="card-body">
                                <p class="text-muted mb-3">
                                    Protect your account by updating your password regularly.
                                </p>

                                <button 
                                    type="button" 
                                    class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#changePasswordModal">
                                    Change Password
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- CHANGE PASSWORD MODAL -->
                    <div class="modal fade" id="changePasswordModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="POST">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Password</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="form-group mb-3">
                                            <label>Current Password</label>
                                            <input type="password" name="current_password" class="form-control" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label>New Password</label>
                                            <input type="password" name="new_password" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" required>
                                        </div>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" name="change_password" class="btn btn-primary">
                                            Update Password
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

    
                    <!-- NOTIFICATION -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Notification Setting</div>
                                <small>Choose what appears in notification bell</small>
                            </div>

                            <div class="card-body">

                                <form method="POST">

                                    <!-- REAL FUNCTION -->
                                    <div class="form-check mb-3">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox"
                                            name="notify_new_user"
                                            value="1"
                                            <?= ($user['notify_new_user'] == 1 ? 'checked' : '') ?>
                                        >

                                        <label class="form-check-label">
                                            New User Registration
                                        </label>
                                    </div>

                                    <!-- DUMMY -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" disabled>
                                        <label class="form-check-label text-muted">
                                            Tasks (Coming Soon)
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" disabled>
                                        <label class="form-check-label text-muted">
                                            Leads (Coming Soon)
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" disabled>
                                        <label class="form-check-label text-muted">
                                            Opportunity (Coming Soon)
                                        </label>
                                    </div>

                                    <button type="submit" name="save_notification" class="btn btn-primary px-4">
                                        Save Changes
                                    </button>

                                </form>

                            </div>
                        </div>
                    </div>

                    <!-- ACTIVITY -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Account Activity</div>
                                <small>Recent activity on your account</small>
                            </div>

                            <div class="card-body">

                                <!-- Last Login -->
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                    <div>
                                        <strong>Last Login</strong><br>
                                        <small class="text-muted">
                                            <?= !empty($user['last_login']) ? date('d M Y h:i A', strtotime($user['last_login'])) : 'No record'; ?>
                                        </small>
                                    </div>
                                    <i class="fas fa-sign-in-alt text-primary"></i>
                                </div>

                                <!-- Created -->
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                    <div>
                                        <strong>Created Account</strong><br>
                                        <small class="text-muted">
                                            <?= !empty($user['created_at']) ? date('d M Y h:i A', strtotime($user['created_at'])) : 'No record'; ?>
                                        </small>
                                    </div>
                                    <i class="fas fa-user-plus text-info"></i>
                                </div>

                                <!-- Profile Updated -->
                                <div class="d-flex justify-content-between border-bottom pb-2 mb-3">
                                    <div>
                                        <strong>Profile Updated</strong><br>
                                        <small class="text-muted">
                                            <?= !empty($user['updated_at']) ? date('d M Y h:i A', strtotime($user['updated_at'])) : 'No record'; ?>
                                        </small>
                                    </div>
                                    <i class="fas fa-user-edit text-success"></i>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

<!-- REQUIRED JS -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script>
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="../assets/js/kaiadmin.min.js"></script>

<script>
setTimeout(function () {
    let alerts = document.querySelectorAll('.auto-dismiss');

    alerts.forEach(function(alert) {
        alert.style.transition = "0.5s";
        alert.style.opacity = "0";

        setTimeout(function(){
            alert.remove();
        }, 500);
    });

}, 3000); // 3 saat
</script>

</body>
</html>