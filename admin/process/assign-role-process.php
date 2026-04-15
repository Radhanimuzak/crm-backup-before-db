<?php
/* FILE        : assign-role-process.php
 * LOKASI      : /admin/process/
 * FUNGSI:
 * Set role user + hantar email aktivasi
 * FLOW:
 * Form Admin (POST user_id, role_id)
          ↓
 * File ini proses
          ↓
 * Update DB (role + token)
          ↓
 * Ambil data user (email)
          ↓
 * Buat link set password
          ↓
 * Hantar email
          ↓
 * Redirect dashboard
          ↓
 *       END */

/* ================= START ================= */
session_start(); // START → mula session admin (tracking login)


/* ================= DEPENDENCY ================= */
require "../../config/database.php"; // AMBIL DB → guna untuk update & ambil data user

require "../../config/mail.php";     // AMBIL MAIL → guna untuk hantar email aktivasi

/* ================= RINGKAS ARAH DATA ================= */
/*
INPUT  → dari form admin (user_id, role_id)
PROSES → file ini (update DB + generate token)
DB     → table users (update role + simpan token)
EMAIL  → hantar link set password ke user
OUTPUT → redirect ke dashboard */


/* ================= INPUT ================= */
/* Data dari form admin (POST) */
$user_id = $_POST['user_id'];   // INPUT → ambil ID user dari form admin
$role_id = $_POST['role_id'];   // INPUT → ambil role dipilih admin


/* ================= PROCESS ================= */
/* Generate token untuk set password */
$token = bin2hex(random_bytes(32)); // PROSES → buat token unik (akan dihantar ke email)


/* ================= DATABASE (UPDATE) ================= */
/* Update role + simpan token */
$query = "UPDATE users SET
    role_id = '$role_id',
    -- SIMPAN role baru
    verify_token = '$token'
WHERE user_id = '$user_id'";


/* Jalankan query */
mysqli_query($conn,$query); // EXECUTE → hantar query ke DB (update berjaya/gagal)


/* ================= END ================= */
/* END STEP:
- Role user sudah diupdate dalam DB
- Token sudah disimpan
- Step seterusnya → ambil email user & hantar link aktivasi */


/* ================= DATABASE (SELECT) ================= */
/* Ambil email + nama role user dari database */
$user_query = mysqli_query($conn,"
SELECT 
users.user_email,
roles.role_name
FROM users
LEFT JOIN roles ON users.role_id = roles.role_id
WHERE users.user_id='$user_id'
");

/* Ambil hasil query */
$user = mysqli_fetch_assoc($user_query);

/* Mapping data */
$email = $user['user_email'];     // Email user (untuk hantar email)
$role_name = $user['role_name'];  // Nama role user (untuk paparan/email)

/* ================= PROCESS (LINK) ================= */
/* Bina link set password (guna token) */
$link = "http://localhost/kaiadmin-lite-1.2.0/auth/set-password.php?token=".$token; // OUTPUT → link dihantar dalam email


/* ================= PROCESS (TEMPLATE) ================= */
/* Ambil template email HTML */
$template = file_get_contents("../../email_templates/user_role_assigned.html"); // AMBIL → file template email


/* ================= PROCESS (REPLACE DATA) ================= */
/* Masukkan data ke dalam template */
$template = str_replace("{{create_password_link}}",$link,$template); // GANTI → letak link dalam email
$template = str_replace("{{role}}",$role_name,$template);            // GANTI → letak nama role dalam email


/* ================= OUTPUT (EMAIL) ================= */
/* Hantar email kepada user */
sendMail(
    $email,                                   // TUJUAN → email user
    "Your CRM Account Has Been Activated",     // SUBJEK → tajuk email
    $template                                 // ISI → template yang sudah diganti data
);


/* ================= OUTPUT (REDIRECT) ================= */
/* Redirect balik ke dashboard admin */
header("Location: ../dashboard.php"); // OUTPUT → pindah ke dashboard selepas selesai


/* ================= END ================= */
/* Tamatkan script */
exit(); // END → hentikan semua proses selepas redirect

?>