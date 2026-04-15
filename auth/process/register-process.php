<?php

/* =========================================================
   START PROGRAM
   =========================================================
   File ini adalah PROSES REGISTER USER (BACKEND PROCESS)

   FLOW BESAR:
   FORM REGISTER (register.php)
        ↓ (POST)
   FILE INI (process register)
        ↓
   VALIDASI (username, email, division-unit)
        ↓
   INSERT KE DATABASE (users table)
        ↓
   AMBIL DATA TAMBAHAN (division & unit)
        ↓
   GENERATE EMAIL TEMPLATE
        ↓
   KIRIM EMAIL KE ADMIN
        ↓
   REDIRECT KEMBALI KE register.php (SUCCESS / ERROR)

========================================================= */


/* =========================================================
   STEP 1 — SESSION START
   =========================================================
   Digunakan untuk menyimpan pesan error / success
   yang nanti ditampilkan di halaman register.php
========================================================= */
session_start();


/* =========================================================
   STEP 2 — LOAD CONFIG
   =========================================================
   database.php → koneksi ke database ($conn)
   mail.php     → fungsi kirim email (sendMail)
========================================================= */
require "../../config/database.php";
require "../../config/mail.php";


/* =========================================================
   STEP 3 — AMBIL INPUT DARI FORM (POST)
   =========================================================
   Data ini berasal dari FORM register.php (method POST)

   INPUT SOURCE:
   ../register.php (FORM USER)
========================================================= */
$user_name      = $_POST['user_name'];
$user_fullname  = $_POST['user_fullname'];
$user_email     = $_POST['user_email'];
$user_mobile_no = $_POST['user_mobile_no'];
$user_position  = $_POST['user_position'];
$division_id    = $_POST['division_id'];
$unit_id        = $_POST['unit_id'];


/* =========================================================
   STEP 4 — VALIDASI USERNAME (UNIQUE)
   =========================================================
   CEK ke database:
   users.user_name

   TUJUAN:
   Mencegah username duplicate
========================================================= */
$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_name = ?");
$stmt->bind_param("s", $user_name);
$stmt->execute();
$stmt->store_result();

/* =========================================================
   JIKA USERNAME SUDAH ADA:
   → STOP PROCESS
   → SIMPAN ERROR KE SESSION
   → REDIRECT BALIK KE FORM
========================================================= */
if($stmt->num_rows > 0){

    $_SESSION['register_error'] = "Username already used";
    header("Location: ../register.php");
    exit();

}


/* =========================================================
   STEP 5 — VALIDASI EMAIL (UNIQUE)
   =========================================================
   CEK ke database:
   users.user_email

   TUJUAN:
   Mencegah email duplicate
========================================================= */
$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->store_result();

/* =========================================================
   JIKA EMAIL SUDAH ADA:
   → STOP PROCESS
   → SIMPAN ERROR
   → REDIRECT
========================================================= */
if($stmt->num_rows > 0){

    $_SESSION['register_error'] = "Email already registered";
    header("Location: ../register.php");
    exit();

}


/* =========================================================
   STEP 6 — VALIDASI RELASI DIVISION & UNIT
   =========================================================
   CEK:
   apakah unit_id tersebut benar milik division_id

   DATABASE:
   table units (unit_id, division_id)

   TUJUAN:
   memastikan data konsisten (tidak salah mapping)
========================================================= */
$stmt = $conn->prepare("SELECT unit_id FROM units WHERE unit_id=? AND division_id=?");
$stmt->bind_param("ii", $unit_id, $division_id);
$stmt->execute();
$stmt->store_result();

/* =========================================================
   JIKA TIDAK VALID:
   → STOP PROCESS
   → ERROR
========================================================= */
if($stmt->num_rows == 0){

    $_SESSION['register_error'] = "Selected unit does not belong to the selected division.";
    header("Location: ../register.php");
    exit();

}


/* =========================================================
   STEP 7 — INSERT USER KE DATABASE
   =========================================================
   TABLE: users

   DATA FLOW:
   FORM → PHP → DATABASE

   STATUS DEFAULT:
   'inactive' → user belum aktif sebelum assign role
========================================================= */
$stmt = $conn->prepare("

INSERT INTO users(

user_name,
user_fullname,
user_email,
user_mobile_no,
user_position,
division_id,
unit_id,
user_status,
created_at,
updated_at

)

VALUES(?,?,?,?,?,?,?,'inactive',NOW(),NOW())

");

$stmt->bind_param(
"sssssii",
$user_name,
$user_fullname,
$user_email,
$user_mobile_no,
$user_position,
$division_id,
$unit_id
);

$stmt->execute();


/* =========================================================
   STEP 8 — AMBIL USER_ID TERBARU
   =========================================================
   Digunakan untuk:
   → link assign role
========================================================= */
$user_id = $conn->insert_id;


/* =========================================================
   STEP 9 — AMBIL NAMA DIVISION
   =========================================================
   SOURCE:
   table divisions

   DIGUNAKAN UNTUK:
   isi email template
========================================================= */
$division_query = mysqli_query($conn,"SELECT division_name FROM divisions WHERE division_id='$division_id'");
$division = mysqli_fetch_assoc($division_query);
$division_name = $division['division_name'];


/* =========================================================
   STEP 10 — AMBIL NAMA UNIT
   =========================================================
   SOURCE:
   table units

   DIGUNAKAN UNTUK:
   isi email template
========================================================= */
$unit_query = mysqli_query($conn,"SELECT unit_name FROM units WHERE unit_id='$unit_id'");
$unit = mysqli_fetch_assoc($unit_query);
$unit_name = $unit['unit_name'];


/* =========================================================
   STEP 11 — GENERATE LINK ASSIGN ROLE
   =========================================================
   FLOW:
   ADMIN akan klik link ini → masuk ke:
   /admin/user-role-assignment.php

   PARAMETER:
   user_id (yang baru dibuat)

========================================================= */
$link = "http://localhost/kaiadmin-lite-1.2.0/admin/user-role-assignment.php?user_id=".$user_id;


/* =========================================================
   STEP 12 — LOAD EMAIL TEMPLATE
   =========================================================
   FILE SOURCE:
   ../../email_templates/admin_new_user.html

   LALU:
   replace placeholder dengan data user
========================================================= */
$template = file_get_contents("../../email_templates/admin_new_user.html");

$template = str_replace("{{username}}",$user_name,$template);
$template = str_replace("{{fullname}}",$user_fullname,$template);
$template = str_replace("{{email}}",$user_email,$template);
$template = str_replace("{{mobile}}",$user_mobile_no,$template);
$template = str_replace("{{position}}",$user_position,$template);
$template = str_replace("{{division}}",$division_name,$template);
$template = str_replace("{{unit}}",$unit_name,$template);
$template = str_replace("{{assign_link}}",$link,$template);


/* =========================================================
   STEP 13 — AMBIL SEMUA EMAIL ADMIN
   =========================================================
   SOURCE:
   users table

   KONDISI:
   role_id = 1 (ADMIN)
========================================================= */
$admins = mysqli_query($conn,"SELECT user_email FROM users WHERE role_id=1");


/* =========================================================
   STEP 14 — KIRIM EMAIL KE SEMUA ADMIN
   =========================================================
   FUNCTION:
   sendMail()

   FLOW:
   PHP → mail.php → SMTP → Email Admin

========================================================= */
while($admin = mysqli_fetch_assoc($admins)){

    sendMail(
        $admin['user_email'],
        "New User Registration",
        $template
    );

}


/* =========================================================
   STEP 15 — SET SUCCESS MESSAGE
   =========================================================
   Disimpan ke SESSION untuk ditampilkan di UI
========================================================= */
$_SESSION['register_success'] = true;


/* =========================================================
   STEP 16 — REDIRECT (END FLOW)
   =========================================================
   Kembali ke:
   ../register.php

   STATUS:
   SUCCESS
========================================================= */
header("Location: ../register.php");
exit();


/* =========================================================
   END PROGRAM
   =========================================================
========================================================= */

?>