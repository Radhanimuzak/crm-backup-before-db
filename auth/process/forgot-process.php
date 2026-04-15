<?php

/* =========================================================
   START PROGRAM
   =========================================================
   Titik awal file ini dijalankan

   FILE INI BERFUNGSI UNTUK:
   → PROSES "FORGOT PASSWORD"
   → Kirim email reset password ke user
========================================================= */
session_start();

/* =========================================================
   LOAD CONFIG (SUMBER SISTEM)
   =========================================================
   FILE YANG DIPANGGIL:
   1. ../../config/database.php
      → koneksi ke database ($conn)

   2. ../../config/mail.php
      → fungsi kirim email (sendMail)

   ARAH:
   File ini bergantung ke folder:
   → config/database.php
   → config/mail.php
========================================================= */
require "../../config/database.php";
require "../../config/mail.php";

/* =========================================================
   INPUT DARI USER (FORM LOGIN / FORGOT PASSWORD)
   =========================================================
   DATA MASUK DARI:
   → METHOD POST (form forgot password)

   FIELD:
   → name="email"

   FLOW:
   USER INPUT (form)
        ↓
   $_POST['email']
        ↓
   disimpan ke $email
========================================================= */
$email = $_POST['email'];

/* =========================================================
   STEP 1 — CEK EMAIL DI DATABASE
   =========================================================
   TUJUAN:
   - memastikan email terdaftar

   FLOW:
   INPUT email
        ↓
   query ke database (users)
        ↓
   cek ada atau tidak

   OUTPUT:
   - jika TIDAK ADA → redirect ke login + error
   - jika ADA → lanjut proses
========================================================= */
$query = mysqli_query($conn,"SELECT * FROM users WHERE user_email='$email'");

if(mysqli_num_rows($query)==0){

    /* =========================================================
       EMAIL TIDAK DITEMUKAN
       =========================================================
       ACTION:
       - simpan pesan error ke session
       - redirect ke halaman login

       ARAH:
       → ../login.php
    ========================================================= */
    $_SESSION['forgot_error'] = "Email not found";

    header("Location: ../login.php");
    exit();
}

/* =========================================================
   EMAIL DITEMUKAN
   =========================================================
   Ambil data user dari database

   OUTPUT:
   - $user berisi data user (id, email, dll)
========================================================= */
$user = mysqli_fetch_assoc($query);

/* =========================================================
   STEP 2 — GENERATE TOKEN RESET PASSWORD
   =========================================================
   TUJUAN:
   - membuat token unik untuk reset password

   CARA:
   - random_bytes(32) → generate random
   - bin2hex → ubah ke string

   OUTPUT:
   - $token (string unik)
========================================================= */
$token = bin2hex(random_bytes(32));

/* =========================================================
   STEP 3 — SET WAKTU EXPIRE TOKEN
   =========================================================
   TUJUAN:
   - membatasi waktu reset password (15 menit)

   FLOW:
   waktu sekarang
        ↓
   +15 menit
        ↓
   simpan ke $expire
========================================================= */
$expire = date("Y-m-d H:i:s", strtotime("+15 minutes"));

/* =========================================================
   STEP 4 — SIMPAN TOKEN KE DATABASE
   =========================================================
   TUJUAN:
   - menyimpan token & expired time ke table users

   FIELD DATABASE:
   - verify_token
   - token_expired_at

   FLOW:
   token + expire
        ↓
   UPDATE users
        ↓
   disimpan di database

   CATATAN:
   token ini nanti akan dipakai di reset-password.php
========================================================= */
mysqli_query($conn,"UPDATE users SET

    verify_token='$token',
    token_expired_at='$expire'

    WHERE user_email='$email'");

/* =========================================================
   STEP 5 — BUAT LINK RESET PASSWORD
   =========================================================
   TUJUAN:
   - membuat URL yang akan dikirim ke email user

   FORMAT LINK:
   reset-password.php?token=xxxx

   ARAH:
   → /auth/reset-password.php

   FLOW:
   token
        ↓
   dimasukkan ke URL
        ↓
   jadi link reset
========================================================= */
$link = "http://localhost/kaiadmin-lite-1.2.0/auth/reset-password.php?token=".$token;

/* =========================================================
   STEP 6 — LOAD TEMPLATE EMAIL
   =========================================================
   TUJUAN:
   - mengambil file HTML email

   SUMBER FILE:
   → ../../email_templates/reset_password.html

   FLOW:
   file HTML
        ↓
   dimasukkan ke $template
========================================================= */
$template = file_get_contents("../../email_templates/reset_password.html");

/* =========================================================
   STEP 7 — MASUKKAN LINK KE TEMPLATE
   =========================================================
   TUJUAN:
   - mengganti placeholder {{reset_link}} dengan link asli

   FLOW:
   template HTML
        ↓
   replace {{reset_link}}
        ↓
   menjadi link reset asli
========================================================= */
$template = str_replace("{{reset_link}}",$link,$template);

/* =========================================================
   STEP 8 — KIRIM EMAIL
   =========================================================
   TUJUAN:
   - mengirim email ke user

   FUNCTION:
   sendMail(to, subject, content)

   SUMBER FUNCTION:
   → ../../config/mail.php

   FLOW:
   email user
        ↓
   kirim email reset password
========================================================= */
sendMail($email,"Reset Password",$template);

/* =========================================================
   STEP 9 — SUCCESS MESSAGE
   =========================================================
   TUJUAN:
   - memberi info bahwa email berhasil dikirim

   FLOW:
   set session success
        ↓
   redirect ke login
========================================================= */
$_SESSION['forgot_success'] = true;

/* =========================================================
   STEP 10 — REDIRECT AKHIR
   =========================================================
   TUJUAN:
   - kembali ke halaman login

   ARAH:
   → ../login.php

   END FLOW:
   USER:
   - cek email
   - klik link reset
========================================================= */
header("Location: ../login.php");
exit();

/* =========================================================
   END PROGRAM
========================================================= */
?>