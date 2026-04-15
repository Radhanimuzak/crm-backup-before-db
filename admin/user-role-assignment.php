<?php
/* =========================================================
   STEP 1 — MULA (ENTRY POINT)
   =========================================================
   File ini dijalankan apabila URL dipanggil               // entry point utama file ini

   SUMBER AKSES:
   → Boleh dari mana-mana page / link / button             

   DATA MASUK:
   → user_id (GET / URL)                                   // ambil ID user dari URL

   CONTOH:
   user-role-assignment.php?user_id=5                      // user_id dihantar = 5

   FLOW RINGKAS:
   Browser / mana-mana page
        ↓
   URL (GET user_id)
        ↓
   file ini proses data

   TUJUAN:
   → Kenal pasti user berdasarkan user_id                  // untuk assign role user

   END STEP INI:
   → user_id akan digunakan dalam proses seterusnya        // database / update role
========================================================= */

$required_role = 1; // Tetapkan role minimum (contoh: admin = 1) → untuk kawal akses

/* =========================================================
   STEP 2 — VALIDASI LOGIN & ROLE
   =========================================================
   FILE:
   → ../config/auth-check.php                 // SUMBER: file check login & role

   FUNGSI:
   → Pastikan hanya admin (role_id = 1) boleh akses  // kawal akses sistem

   FLOW:
   file ini → require auth-check → validasi user

   END STEP:
   → Jika gagal: biasanya redirect / block akses
   → Jika lulus: terus ke step seterusnya
========================================================= */
require "../config/auth-check.php"; // panggil file auth untuk semak login & role

/* =========================================================
   STEP 3 — KONEKSI DATABASE
   =========================================================
   FILE:
   → ../config/database.php                  // SUMBER: config database

   OUTPUT:
   → $conn                                   // connection untuk query DB

   FLOW:
   file ini → require database → dapat $conn

   END STEP:
   → $conn digunakan untuk ambil / update data user
========================================================= */
require "../config/database.php"; // sambung ke database

/* =========================================================
   STEP 4 — AMBIL INPUT user_id DARI URL
========================================================= */

// STEP 1: Semak sama ada 'user_id' wujud dalam URL (GET)  // validasi input
// Contoh: user-edit.php?user_id=5
if(!isset($_GET['user_id'])){ // jika tiada user_id

// STEP 2: Jika TIADA → paparkan error                   // elak data kosong
echo "User not found"; // mesej error kepada user

// STEP 3: Hentikan script                              // stop supaya tak terus proses
exit(); // END jika gagal (tidak ada user_id)
}

/* DATA MASUK DARI URL */
$user_id = $_GET['user_id']; // ambil user_id dari URL untuk proses seterusnya

/* =========================================================
   STEP 5 — QUERY DATABASE
   =========================================================
   TUJUAN:
   → Ambil data user + division + unit

   INPUT:
   → $user_id (dari URL)

   PROSES:
   → Query ke database (users + join divisions + units)

   END STEP:
   → Hasil query disimpan dalam $user (array)
========================================================= */

// STEP 1: Jalankan query ke database
$query = mysqli_query($conn,"
SELECT 
users.*, 
divisions.division_name, 
units.unit_name
FROM users
LEFT JOIN divisions ON users.division_id = divisions.division_id
LEFT JOIN units ON users.unit_id = units.unit_id
WHERE users.user_id='$user_id'
"); // END QUERY → hantar arahan SQL ke database & dapatkan result ($query)

// STEP 2: Ambil hasil query jadi array
$user = mysqli_fetch_assoc($query); // END DATA → tukar result kepada array untuk digunakan

/* =========================================================
   FLOW RINGKAS
   =========================================================
   $user_id (URL)
        ↓
   query database
        ↓
   hasil ($query)
        ↓
   fetch → $user

   END KESELURUHAN:
   → $user digunakan untuk paparan / edit / assign role
========================================================= */

/* =========================================================
   STEP 6 — VALIDASI USER
   =========================================================
   TUJUAN:
   → Pastikan data user wujud selepas query database

   INPUT:
   → $user (hasil dari mysqli_fetch_assoc)

   END STEP:
   → Jika tiada user → hentikan proses (exit)
   → Jika ada → terus ke step seterusnya
========================================================= */

if(!$user){ /* semak jika $user kosong / FALSE (data tidak jumpa) */

echo "User not found"; /* paparkan mesej error kepada user */

exit(); /* END: hentikan program supaya tidak terus ke proses seterusnya */

}

/* =========================================================
   STEP 7 — SEMAK ROLE
   =========================================================
   TUJUAN:
   → Semak sama ada user sudah ada role

   FLOW:
   $user (dari database)
        ↓
   semak role_id
        ↓
   jika ada → paparkan halaman ini & STOP
        ↓
   jika tiada → terus ke proses assign role

   END STEP:
   → Block ini hanya untuk OUTPUT (tiada proses database)
========================================================= */

if($user['role_id'] != NULL){ /* jika user sudah ada role → masuk block ini & hentikan flow */

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8"> <!-- set encoding -->

<title>Role Already Assigned</title> <!-- tajuk browser -->

<link rel="stylesheet" href="../assets/css/bootstrap.min.css"> <!-- style bootstrap -->
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css"> <!-- style admin template -->

</head>

<body style="background:#f5f7fd;font-family:Arial"> <!-- UI sahaja (tiada backend) -->

<div class="container"> <!-- pembungkus utama -->

    <div class="row justify-content-center"> <!-- susun ke tengah -->

        <div class="col-lg-6"> <!-- lebar 50% -->

            <div class="card shadow mt-5"> <!-- box UI -->

                <div class="card-body text-center p-5"> <!-- isi utama -->

                    <h3 class="fw-bold mb-3">
                        Role Already Assigned
                    </h3> <!-- tajuk info -->

                    <p class="text-muted">
                        You have already assigned a role to this user.
                        This registration request has been completed.
                    </p> <!-- mesej kepada user -->

                </div> <!-- END card body (tiada form / tiada submit) -->

            </div> <!-- END card -->

        </div> <!-- END column -->

    </div> <!-- END row -->

</div> <!-- END container -->

<!-- END UI:
     → hanya paparan
     → tiada input / POST / GET / database
-->

</body>
</html>

<?php
/* =========================================================
   END FLOW (PENTING)
   =========================================================
   TUJUAN:
   → Hentikan keseluruhan sistem jika role sudah ada

   FLOW:
   user masuk → semak role → jika ada → paparan keluar → STOP

   END:
   → Tiada proses lanjut (tiada form / DB / redirect)
========================================================= */

exit(); /* STOP TOTAL: hentikan semua proses selepas ini */

/* =========================================================
   PENUTUP IF
   =========================================================
   → Tutup blok if($user['role_id'] != NULL)

   JIKA FALSE:
   → skip block atas
   → terus ke proses assign role (step seterusnya)

   END:
   → sambung flow normal jika tiada role
========================================================= */
}

?>

<!DOCTYPE html>
<!-- START: browser mula baca HTML5 -->

<html lang="en">
<head>

<meta charset="UTF-8"> <!-- set encoding (tiada DB / input) -->

<title>Assign User Role</title> <!-- tajuk tab browser (static) -->

<link rel="stylesheet" href="../assets/css/bootstrap.min.css"> <!-- load CSS layout -->
<link rel="stylesheet" href="../assets/css/kaiadmin.min.css"> <!-- load template admin -->

<style>

/* UI sahaja (tiada backend / DB / input) */

body{ /* style global halaman */
background:#f5f7fd;
font-family:'Public Sans',sans-serif;
padding:40px;
}

.card{ /* kotak UI */
border-radius:16px;
border:none;
}

.section-title{ /* label kecil */
font-size:14px;
color:#6c757d;
margin-bottom:4px;
}

.info-value{ /* isi data */
font-weight:600;
font-size:15px;
}

.info-box{ /* pembungkus data */
padding:12px 0;
border-bottom:1px solid #f1f1f1;
}

.role-box{ /* area assign role */
background:#f8f9fc;
padding:20px;
border-radius:12px;
margin-top:20px;
}

.btn-submit{ /* butang hantar → nanti ke backend */
background:#2a2f5b;
color:white;
border:none;
height:45px;
padding:0 28px;
border-radius:8px;
}

.btn-cancel{ /* butang batal */
background:#dee2e6;
border:none;
height:45px;
padding:0 28px;
border-radius:8px;
}

.modal-content{ /* popup UI */
border-radius:14px;
}

.success-icon{ /* icon berjaya */
width:120px;
height:120px;
border-radius:50%;
border:4px solid #28a745;
display:flex;
align-items:center;
justify-content:center;
margin:auto;
margin-bottom:20px;
position:relative;
}

.success-icon::after{ /* animasi check */
content:'';
width:35px;
height:65px;
border-right:6px solid #28a745;
border-bottom:6px solid #28a745;
transform:rotate(45deg);
animation:checkAnim 0.6s ease forwards;
opacity:0;
}

@keyframes checkAnim{ /* animasi */
0%{opacity:0; transform:scale(0.5) rotate(45deg);}
100%{opacity:1; transform:scale(1) rotate(45deg);}
}

</style>

</head> <!-- END HEAD: siap load CSS & setting -->


<!-- START BODY: mula render UI -->
<body>

<div class="container"> <!-- pembungkus utama -->

<div class="row justify-content-center"> <!-- susun tengah -->

<div class="col-lg-8"> <!-- lebar content -->

<div class="card shadow"> <!-- box utama -->

<div class="card-body p-4"> <!-- isi card -->

<h3 class="fw-bold mb-2">
Assign User Role
</h3> <!-- tajuk -->

<p class="text-muted mb-4">
Review the user information and assign the appropriate role.
</p> <!-- arahan kepada admin -->

<hr>

<!-- DATA USER (READ ONLY: dari $user database) -->
<div class="row">

<div class="col-md-6 info-box">
<div class="section-title">Username</div>
<div class="info-value"><?php echo $user['user_name']; ?></div> <!-- dari users.user_name -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Full Name</div>
<div class="info-value"><?php echo $user['user_fullname']; ?></div> <!-- dari database -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Email</div>
<div class="info-value"><?php echo $user['user_email']; ?></div> <!-- dari database -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Mobile Number</div>
<div class="info-value"><?php echo $user['user_mobile_no']; ?></div> <!-- dari database -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Position</div>
<div class="info-value"><?php echo $user['user_position']; ?></div> <!-- dari database -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Division</div>
<div class="info-value"><?php echo $user['division_name']; ?></div> <!-- hasil JOIN division -->
</div>

<div class="col-md-6 info-box">
<div class="section-title">Unit</div>
<div class="info-value"><?php echo $user['unit_name']; ?></div> <!-- hasil JOIN unit -->
</div>

</div> <!-- END DATA USER -->

<!-- END DATA USER → setakat ini hanya paparan (tiada input / DB) -->

<form method="POST" action="process/assign-role-process.php"> <!-- hantar data ke backend (POST) -->

<input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>"> <!-- bawa user_id dari database -->

<div class="role-box">

<label class="form-label fw-semibold mb-2">
Assign Role
</label> <!-- label -->

<select name="role_id" id="roleSelect" class="form-select"> <!-- user pilih role → dihantar sebagai role_id -->
<option value="">Select Role</option>
<option value="1">Admin</option>
<option value="2">Manager</option>
<option value="3">Staff</option>
</select>

</div>

<div class="d-flex gap-3 mt-4">

<button type="button" class="btn btn-submit" onclick="checkRole()">
Submit
</button> <!-- klik → JS checkRole() → validasi → submit ke backend -->

<button type="button" class="btn btn-cancel" onclick="history.back()">
Cancel
</button> <!-- kembali halaman -->

</div>

</form>
<!-- END:
     → hantar: user_id + role_id
     → ke: process/assign-role-process.php -->


<!-- PENUTUP DIV:
     → tutup struktur UI sebelum ini (container → row → col → card → body)
     → END UI utama (paparan halaman) -->
</div>
</div>
</div>
</div>
</div>

<!-- MODAL WARNING: jika role kosong -->
<div class="modal fade" id="roleWarning"> <!-- popup warning -->

<div class="modal-dialog modal-dialog-centered"> <!-- posisi tengah -->

<div class="modal-content"> <!-- isi modal -->

<div class="modal-header">
<h5 class="modal-title fw-bold">Role Required</h5> <!-- tajuk -->
<button class="btn-close" data-bs-dismiss="modal"></button> <!-- tutup -->
</div>

<div class="modal-body">
Please select a role before submitting. <!-- mesej error -->
</div>

<div class="modal-footer">
<button class="btn btn-secondary" data-bs-dismiss="modal">
Done
</button> <!-- tutup modal -->
</div>

</div>
</div>
</div>

<!-- MODAL SUCCESS: jika role dipilih -->
<div class="modal fade" id="roleSuccess"> <!-- popup berjaya -->

<div class="modal-dialog modal-dialog-centered"> <!-- tengah -->

<div class="modal-content"> <!-- isi -->

<div class="modal-body text-center">

<div class="success-icon"></div> <!-- icon -->

<h4 class="fw-bold mt-3">
Role Assigned Successfully
</h4> <!-- mesej berjaya -->

<p class="text-muted">
The user role has been successfully assigned.
An email will be sent to the registered email address with instructions to create a password.
</p> <!-- info -->

<!-- FLOW:
     klik submit → checkRole()
     → kosong: modal warning
     → ada: modal success
     → DONE: submit form ke backend -->


<!-- BUTTON DONE: trigger hantar form ke backend -->
<button class="btn btn-submit mt-3" onclick="submitForm()">
Done
</button> <!-- klik → submitForm() → hantar ke process/assign-role-process.php -->

</div>
</div>
</div>
</div> <!-- END modal success (tutup semua div modal) -->

<!-- LOAD JS: fungsi modal & interaksi -->
<script src="../assets/js/core/jquery-3.7.1.min.js"></script> <!-- support JS -->
<script src="../assets/js/core/bootstrap.min.js"></script> <!-- modal bootstrap -->

<script>

/* VALIDASI FRONTEND: semak role sebelum hantar */
function checkRole(){

    let role = document.getElementById("roleSelect").value; /* ambil nilai dropdown */

    if(role === ""){ /* jika kosong */
        let warning = new bootstrap.Modal(document.getElementById('roleWarning'));
        warning.show(); /* papar modal warning */
    }else{ /* jika ada */
        let successModal = new bootstrap.Modal(document.getElementById('roleSuccess'));
        successModal.show(); /* papar modal success */
    }

}
/* END:
   → user klik submit
   → checkRole() validasi
   → modal keluar
   → klik DONE → submitForm() → hantar ke backend */

/* STEP 9 — HANTAR KE BACKEND
   → hantar user_id + role_id ke process/assign-role-process.php */
function submitForm(){
    document.querySelector("form").submit(); /* submit form (POST ke backend) */
}

</script> <!-- END JS: tamat logik frontend -->

</body> <!-- END BODY: tamat paparan UI -->

</html> <!-- END HTML: tamat keseluruhan halaman -->