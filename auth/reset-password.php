<?php
/* =====================================================
   [START] VALIDASI TOKEN RESET PASSWORD
===================================================== */
/*
   Ambil sambungan database dari:
   → ../config/database.php

   Fungsi:
   - Menghasilkan connection ($conn)
   - Digunakan untuk query ke database */
require "../config/database.php";

/* AMBIL TOKEN DARI URL (GET)

   Contoh:
   reset-password.php?token=abc123

   Jika tiada token:
   → default = '' */
$token = $_GET['token'] ?? '';

/* SEMAK TOKEN DALAM DATABASE (table: users)
   Syarat:
   1. verify_token mesti sama
   2. token belum expired
      (token_expired_at > NOW())

   Tujuan:
   - Pastikan user sah untuk reset password */
$query = mysqli_query($conn,"
SELECT * FROM users
WHERE verify_token='$token'
AND token_expired_at > NOW()
");

/* JIKA TOKEN TAK SAH ATAU EXPIRED

   Keadaan:
   - Data tiada dalam database

   Tindakan:
   - Papar mesej error
   - Hentikan sistem (exit)
   Flow berhenti di sini jika gagal */
if(mysqli_num_rows($query)==0){
    echo "Invalid or expired token";
    exit();
}
/* ===========[END]============= */

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password - CRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- =====================================================
   LOAD FONT AWESOME (ICON)
   Fungsi:
   - Ikon mata untuk show / hide password
===================================================== */-->
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- =====================================================
   LOAD CSS TEMPLATE (KAIADMIN)
   Sumber:
   /kaiadmin-lite-1.2.0/

   Fungsi:
   - Design UI (form, button, layout)
===================================================== -->
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
<link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

<style>
/* ==================== STYLE CUSTOM HALAMAN RESET PASSWORD ==================== */

/* Background & font utama halaman */
body{
    background:#f5f7fd;
    font-family:'Public Sans',sans-serif;
}

/* Card form (sudut bulat) */
.reset-card{
    border-radius:18px;
}

/* Button utama */
.btn-kai-dark{
    background:#2A2F5B;
    border:none;
    color:#fff;
    height:48px;
    font-weight:500;
}

/* Hover button */
.btn-kai-dark:hover{
    background:#1f2447;
    color:#fff;
}

/* Saiz input field */
.form-control{
    height:48px;
}

/* Cursor pointer untuk icon (mata) */
.input-group-text{
    cursor:pointer;
}

</style>
</head>

<body>
<!-- START: UI Reset Password (papar form ke user) -->

<div class="container vh-100 d-flex justify-content-center align-items-center">
<!-- layout: centerkan form di tengah skrin -->

    <div class="card shadow-lg p-5 reset-card" style="max-width:450px;width:100%;">
    <!-- card utama untuk form -->

        <h4 class="fw-bold mb-4">Create New Password</h4> <!-- tajuk -->

        <form method="POST" action="process/reset-password-process.php" onsubmit="return validatePassword()">
        <!--
        INPUT → dari user
        HANTAR → process/reset-password-process.php (backend)
        FLOW → user isi → validate JS → POST ke backend -->

        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <!-- AMBIL → dari URL ($_GET token)
        HANTAR → ke backend (kenal pasti user) -->

        <div class="mb-3">

            <label class="mb-2">New Password</label>

            <div class="input-group">

                <input type="password" class="form-control"
                name="password" id="newPassword"
                placeholder="Enter new password" required>
                <!--
                INPUT → password user
                HANTAR → backend (name="password")
                DIGUNA → update password dalam database
                -->

                <button type="button" class="input-group-text bg-white"
                onclick="togglePassword('newPassword','eye1')">
                <!--
                FUNGSI → show/hide password (frontend sahaja)
                TIDAK hantar ke backend
                -->

                <i class="fa-solid fa-eye" id="eye1"></i>

                </button>

            </div>

        </div>
<!-- END: UI (data akan dihantar ke backend bila submit) -->

<!-- INPUT SAHKAN PASSWORD -->
<div class="mb-4">

    <label class="mb-2">Confirm Password</label>

    <div class="input-group">

        <input type="password" class="form-control"
        name="confirm_password" id="confirmPassword"
        placeholder="Re-enter password" required>
        <!--
        INPUT → ulang kata laluan
        TUJUAN → pastikan sama dengan password
        HANTAR → ke backend (confirm_password)
        -->

        <button type="button" class="input-group-text bg-white"
        onclick="togglePassword('confirmPassword','eye2')">
        <!-- FUNGSI → papar/sembunyi password (frontend sahaja) -->

        <i class="fa-solid fa-eye" id="eye2"></i>

        </button>

    </div>

</div>


<!-- BUTTON HANTAR -->
<button type="submit" class="btn btn-kai-dark w-100">
Update Password

</button>
<!--
AKSI → hantar data ke backend

FLOW:
klik → validatePassword() (JS)
→ jika sama → POST ke:
   process/reset-password-process.php
→ backend:
   - semak token
   - kemaskini password dalam database -->

</form>

</div>
</div>
<!-- END UI (data dihantar ke backend untuk proses reset password) -->

<!-- LOAD JS (library frontend: UI & interaction sahaja) -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>

<script>

/* =====================================================
   FUNGSI: TOGGLE PASSWORD
   → papar / sembunyi kata laluan (frontend sahaja)
   → TIADA hantar ke backend
===================================================== */
function togglePassword(inputId,iconId){

    var input = document.getElementById(inputId); // ambil input password (dari form)
    var icon  = document.getElementById(iconId);  // ambil ikon mata

    if(input.type==="password"){
        input.type="text"; // papar password ke user
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }else{
        input.type="password"; // sembunyi semula
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }

}

/* =====================================================
   FUNGSI: VALIDASI PASSWORD
   → semak sebelum hantar ke backend

   FLOW:
   ambil input → bandingkan → lulus / gagal
===================================================== */
function validatePassword(){

    let pass    = document.getElementById("newPassword").value;     
    // AMBIL → input password dari user

    let confirm = document.getElementById("confirmPassword").value; 
    // AMBIL → confirm password dari user

    if(pass !== confirm){

        alert("Password tidak sama"); 
        // OUTPUT → mesej kepada user

        return false; 
        // STOP → data TIDAK dihantar ke backend
    }

    return true; 
    /* LULUS → form akan hantar ke: 
       process/reset-password-process.php (backend)
     → backend akan update password dalam database */
}

</script>

</body>
</html>

<!-- END: FILE (flow tamat di frontend, sambung di backend file lain) -->