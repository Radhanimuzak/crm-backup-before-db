<?php

/* =========================================================
   MULA PROGRAM
   File ini digunakan untuk PROSES RESET PASSWORD (backend)

   Flow:
   User isi borang reset password → submit →
   data dihantar ke file ini (method POST)
   ========================================================= */


/* =========================================================
   SAMBUNG KE DATABASE
   Ambil sambungan database dari:
   ../../config/database.php

   Tujuan:
   Supaya kod ini boleh akses table "users"
   ========================================================= */
require "../../config/database.php";


/* =========================================================
   AMBIL INPUT DARI BORANG (FRONTEND)

   Data dihantar dari halaman reset password

   Input:
   - token            → token reset dari email
   - password         → password baru
   - confirm_password → sahkan password

   Flow:
   Form → POST → masuk ke sini
   ========================================================= */
$token   = $_POST['token'];
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];


/* =========================================================
   VALIDASI PASSWORD

   Semak sama ada:
   password == confirm_password

   Jika TAK sama:
   → hentikan proses
   → paparkan mesej error
   ========================================================= */
if($password !== $confirm){

    echo "Password does not match";
    exit(); // HENTI PROGRAM

}


/* =========================================================
   HASH PASSWORD

   Tukar password kepada bentuk hash (encrypted)

   Kenapa?
   → untuk keselamatan (tidak simpan password asal)

   Fungsi:
   password_hash()
   ========================================================= */
$hash = password_hash($password,PASSWORD_DEFAULT);


/* =========================================================
   SEMAK TOKEN DALAM DATABASE

   Semak:
   1. Token wujud dalam table users
   2. Token masih sah (belum expired)

   Ambil data dari:
   table users

   Flow:
   Database → users → verify_token
   ========================================================= */
$query = mysqli_query($conn,"
SELECT * FROM users
WHERE verify_token='$token'
AND token_expired_at > NOW()
");


/* =========================================================
   VALIDASI TOKEN

   Jika:
   - token tidak jumpa ATAU
   - token sudah expired

   Maka:
   → hentikan proses
   → paparkan error
   ========================================================= */
if(mysqli_num_rows($query)==0){

    echo "Invalid or expired token";
    exit(); // HENTI PROGRAM

}


/* =========================================================
   UPDATE PASSWORD KE DATABASE

   Jika token sah:

   Update table: users

   Data yang dikemaskini:
   - user_password      → password baru (hash)
   - verify_token       → kosongkan (NULL)
   - token_expired_at   → kosongkan (NULL)
   - updated_at         → masa sekarang

   Flow:
   Input → proses → simpan ke database
   ========================================================= */
mysqli_query($conn,"
UPDATE users SET

user_password='$hash',
verify_token=NULL,
token_expired_at=NULL,
updated_at = NOW()

WHERE verify_token='$token'
");


/* =========================================================
   REDIRECT KE HALAMAN LOGIN

   Selepas berjaya reset password:
   → hantar user ke halaman login

   Lokasi:
   ../login.php

   Flow akhir:
   Backend selesai → pergi ke login page
   ========================================================= */
header("Location: ../login.php");
exit();


/* =========================================================
   TAMAT PROGRAM
   Semua proses selesai
   ========================================================= */

?>