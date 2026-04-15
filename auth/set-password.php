<?php

/* =====================================================
   [START FILE]
   Validasi token reset password
===================================================== */

require "../config/database.php";   /* ambil koneksi DB */

if(!isset($_GET['token'])){   /* cek token dari URL */
    echo "Invalid link";
    exit();
}

$token = $_GET['token'];   /* ambil token */

$query = mysqli_query($conn,"SELECT * FROM users WHERE verify_token='$token'"); 
/* cari token dalam DB */

if(mysqli_num_rows($query)==0){   /* token tak valid */
    echo "<h2 style='text-align:center;margin-top:100px;font-family:Arial'>
    Password already created or link invalid
    </h2>";
    exit();
}

$user = mysqli_fetch_assoc($query);   /* ambil data user */

?>

<!DOCTYPE html>
<html lang="en">
<head>

<!-- SETTING -->
<meta charset="UTF-8">
<title>Create Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSS -->
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<!-- ICON -->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* BODY */
body{
    background:#f5f7fd;
    font-family:'Public Sans',sans-serif;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* CARD */
.card{
    width:420px;
    padding:40px;
    border-radius:18px;
}

/* BUTTON */
.btn-kai-dark{
    background:#2a2f5b;
    border:none;
    color:#fff;
    height:48px;
    font-weight:500;
}

.btn-kai-dark:hover{
    background:#111628;
}

/* INPUT */
.form-control{
    height:48px;
}

/* ICON */
.input-group-text{
    cursor:pointer;
}

/* SUCCESS */
.success-icon{
    width:110px;
    height:110px;
    border-radius:50%;
    border:4px solid #28a745;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom:20px;
    position:relative;
}

/* CHECK */
.success-icon::after{
    content:'';
    width:32px;
    height:60px;
    border-right:6px solid #28a745;
    border-bottom:6px solid #28a745;
    transform:rotate(45deg);
    animation:checkAnim .6s ease forwards;
    opacity:0;
}

/* ANIMATION */
@keyframes checkAnim{
    0%{
        opacity:0;
        transform:scale(.5) rotate(45deg);
    }
    100%{
        opacity:1;
        transform:scale(1) rotate(45deg);
    }
}

/* MODAL */
.modal-content{
    border-radius:14px;
}

</style>
</head>

<body> <!-- [START HALAMAN] Dipanggil dari link email (ada token) -->

    <div class="card shadow"> <!-- Container UI -->

        <h3 class="fw-bold mb-3 text-center">
            Create Your Password
        </h3> <!-- Tajuk -->

        <p class="text-muted text-center mb-4">
            Your account has been approved.<br>
            Please create a password to activate your account.
        </p> <!-- Info user -->

        <form id="setPasswordForm" method="POST">
        <!-- [START FORM]
             METHOD: POST
             HANTAR DATA → backend (file sama / process file)
             DATA: token, password, confirm_password -->

            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <!-- TOKEN:
                 SUMBER → $_GET (URL email)
                 FUNGSI → kenal pasti user dalam DB
                 ARAH → frontend → backend -->

            <div class="form-group mb-3">

                <label>New Password</label>

                <div class="input-group">

                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control"
                           placeholder="Enter new password"
                           required>
                    <!-- PASSWORD:
                         INPUT user
                         ARAH → dihantar ke backend (POST)
                         BACKEND → hash + simpan DB -->

                    <button type="button"
                            class="input-group-text bg-white"
                            onclick="togglePassword('password','eye1')">
                    <!-- TOGGLE:
                         UI sahaja (show/hide)
                         TAK dihantar ke backend -->

                        <i class="fa-solid fa-eye" id="eye1"></i>
                    </button>

                </div>

            </div>


            <div class="form-group mb-4">

                <label>Confirm Password</label>

                <div class="input-group">

                    <input type="password"
                           name="confirm_password"
                           id="confirm_password"
                           class="form-control"
                           placeholder="Confirm password"
                           required>
                    <!-- CONFIRM PASSWORD:
                         INPUT user
                         BACKEND → compare dengan password -->

                    <button type="button"
                            class="input-group-text bg-white"
                            onclick="togglePassword('confirm_password','eye2')">
                    <!-- UI sahaja -->

                        <i class="fa-solid fa-eye" id="eye2"></i>
                    </button>

                </div>

            </div>

            <button class="btn btn-kai-dark w-100">
                Set Password
            </button>
            <!-- SUBMIT:
                 ACTION → hantar POST:
                 $_POST['token']
                 $_POST['password']
                 $_POST['confirm_password']
                 
                 FLOW BACKEND:
                 1. ambil token
                 2. cari user (DB)
                 3. check password == confirm
                 4. hash password
                 5. update DB
                 6. redirect -->

        </form> <!-- [END FORM] -->

    </div>

<!-- ========================== SUCCESS MODAL ========================== -->
<div class="modal fade" id="successModal"> <!-- Modal → dipaparkan bila success -->

    <div class="modal-dialog modal-dialog-centered"> <!-- Posisi tengah -->

        <div class="modal-content">

            <div class="modal-body text-center p-4"> <!-- Body modal -->

                <div class="success-icon"></div> <!-- Icon visual -->

                <h4 class="fw-bold">
                    Password Created
                </h4> <!-- Tajuk success -->

                <p class="text-muted">
                    Your password has been successfully created.
                </p> <!-- Info user -->

                <button
                    class="btn btn-kai-dark mt-3"
                    onclick="redirectLogin()"> <!-- Klik → pergi ke login -->
                    Go to Login
                </button>

            </div>

        </div>

    </div>

</div>

<!-- ========================== LOAD JS ========================== -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script> <!-- jQuery (manipulasi DOM) -->

<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script> <!-- Bootstrap (modal function) -->

<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script> <!-- Theme UI -->

<script>

/* ========================== TOGGLE PASSWORD ========================== */
function togglePassword(fieldId,iconId){ // input: id input + id icon

    var input = document.getElementById(fieldId); // ambil field password
    var icon  = document.getElementById(iconId);  // ambil icon mata

    if(input.type === "password"){ // jika masih hidden

        input.type = "text"; // tukar jadi nampak
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");

    }else{

        input.type = "password"; // tukar balik hidden
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");

    }
}

/* ========================== SUBMIT FORM ========================== */
document.getElementById("setPasswordForm").addEventListener("submit",function(e){

    e.preventDefault(); // stop submit default → validate dulu

    var pass    = document.getElementById("password").value;         // ambil password
    var confirm = document.getElementById("confirm_password").value; // ambil confirm

    if(pass !== confirm){ // check sama atau tidak

        alert("Password does not match!"); // error
        return; // stop → tak hantar ke backend

    }

/* ========================== AJAX SAVE PASSWORD ========================== */
$.post("process/set-password-process.php", $(this).serialize(), function(){
    /* HANTAR DATA → ke backend file:
    process/set-password-process.php
    DATA dihantar:
    - token
    - password
    - confirm_password
    (ambil dari form → serialize) */

    showSuccess(); // bila backend response (success) → panggil function ini
});

}); // END submit event (dari form sebelumnya)

/* ========================== SHOW SUCCESS MODAL ========================== */
function showSuccess(){

    var success = new bootstrap.Modal(document.getElementById('successModal'));
    // ambil modal HTML (id: successModal)

    success.show(); 
    // papar modal → tanda password berjaya diset

}

/* ========================== REDIRECT LOGIN ========================== */
function redirectLogin(){

    window.location.href = "login.php"; 
    // redirect user ke login page

}

</script>

</body>
</html>