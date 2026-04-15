<?php

/* =========================================================
    START PROGRAM
   =========================================================
   FILE INI BERFUNGSI UNTUK:
   → PROSES LOGIN USER

   FLOW BESAR:
   FORM LOGIN (username & password)
        ↓
   FILE INI (VALIDASI + SESSION)
        ↓
   DATABASE (cek user)
        ↓
   REDIRECT ke dashboard sesuai role
========================================================= */
session_start();

/* =========================================================
   LOAD DATABASE
   =========================================================
   SUMBER:
   → ../../config/database.php

   OUTPUT:
   → $conn (koneksi MySQL)

   DIGUNAKAN UNTUK:
   - query user
   - update last login
   - update remember me
========================================================= */
require "../../config/database.php";

/* =========================================================
   STEP 1 — AMBIL INPUT DARI FORM LOGIN
   =========================================================
   SUMBER INPUT:
   → FORM HTML (method POST)

   FIELD:
   - username
   - password
   - redirect (optional)
   - remember (checkbox)

   FLOW:
   USER INPUT
        ↓
   $_POST
        ↓
   disimpan ke variable
========================================================= */
$redirect = $_POST['redirect'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

/* =========================================================
   STEP 2 — VALIDASI USERNAME KE DATABASE
   =========================================================
   TUJUAN:
   - cek apakah username ada di table users

   KEAMANAN:
   - menggunakan prepared statement (ANTI SQL INJECTION)

   FLOW:
   username
        ↓
   query database (users)
        ↓
   hasil disimpan di $result
========================================================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

/* =========================================================
   STEP 3 — CEK USER ADA ATAU TIDAK
   =========================================================
   OUTPUT:
   - jika ADA (num_rows = 1) → lanjut login
   - jika TIDAK ADA → error login
========================================================= */
if ($result->num_rows === 1) {

    /* =========================================================
       AMBIL DATA USER
       =========================================================
       OUTPUT:
       - $user (array data user dari database)
    ========================================================= */
    $user = $result->fetch_assoc();

    /* =========================================================
       STEP 4 — VERIFY PASSWORD
       =========================================================
       TUJUAN:
       - mencocokkan password input dengan hash di database

       FLOW:
       password input
            ↓
       password_verify()
            ↓
       cocok / tidak
    ========================================================= */
    if (password_verify($password, $user['user_password'])) {

        /* =========================================================
           STEP 5 — CEK STATUS AKUN
           =========================================================
           TUJUAN:
           - hanya user dengan status 'active' yang boleh login

           OUTPUT:
           - jika bukan active → STOP + redirect login
        ========================================================= */
        if ($user['user_status'] !== 'active') {

            $_SESSION['error'] = "Your account is still awaiting administrator approval.";
            header("Location: ../login.php");
            exit;
        }

        /* =========================================================
           STEP 6 — REMEMBER ME SYSTEM
           =========================================================
           TUJUAN:
           - menyimpan password (encoded) jika user centang remember

           FLOW:
           checkbox remember
                ↓
           encode password (base64)
                ↓
           simpan ke database (users.remember_password)

           CATATAN:
           - jika tidak dicentang → hapus (NULL)
        ========================================================= */
          if (isset($_POST['remember'])) {

          $remember_hash = password_hash($password, PASSWORD_DEFAULT);

          $stmt_remember = $conn->prepare("UPDATE users SET remember_password = ? WHERE user_id = ?");
          $stmt_remember->bind_param("si", $remember_hash, $user['user_id']);
          $stmt_remember->execute();

          }

         else {

            $stmt_remember = $conn->prepare("UPDATE users SET remember_password = NULL WHERE user_id = ?");
            $stmt_remember->bind_param("i", $user['user_id']);
            $stmt_remember->execute();
        }

        /* =========================================================
           STEP 7 — SET SESSION LOGIN
           =========================================================
           TUJUAN:
           - menyimpan data user ke session

           FLOW:
           user login berhasil
                ↓
           buat session baru (anti session hijacking)
                ↓
           simpan data user

           OUTPUT SESSION:
           - user_id
           - user_name
           - email
           - role
        ========================================================= */
        session_regenerate_id(true);

        $_SESSION['user_id']   = $user['user_id'];

        // BARU (KONSISTEN)
        $_SESSION['user_name'] = $user['user_name'];
        $_SESSION['email']     = $user['user_email'];
        $_SESSION['role']      = $user['role_id'];

        /* =========================================================
           STEP 8 — UPDATE LAST LOGIN
           =========================================================
           TUJUAN:
           - mencatat waktu login terakhir

           FLOW:
           login berhasil
                ↓
           update database (last_login = NOW())
        ========================================================= */
        $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $update->bind_param("i", $user['user_id']);
        $update->execute();

        /* =========================================================
           STEP 9 — VALIDASI REDIRECT CUSTOM
           =========================================================
           TUJUAN:
           - jika ada redirect dari halaman sebelumnya

           VALIDASI:
           - tidak boleh mengandung:
             → admin
             → manager
             → staff

           (untuk mencegah manipulasi URL)

           FLOW:
           redirect input
                ↓
           valid → redirect ke URL tersebut
        ========================================================= */
        if (!empty($redirect)) {

            if (
                strpos($redirect, "admin") === false &&
                strpos($redirect, "manager") === false &&
                strpos($redirect, "staff") === false
            ) {
                header("Location: " . $redirect);
                exit;
            }
        }

        /* =========================================================
           STEP 10 — REDIRECT BERDASARKAN ROLE
           =========================================================
           TUJUAN:
           - mengarahkan user ke dashboard sesuai role

           ROLE MAPPING:
           1 → admin
           2 → manager
           3 → staff

           ARAH FILE:
           → /admin/dashboard.php
           → /manager/dashboard.php
           → /staff/dashboard.php
        ========================================================= */
        switch ($user['role_id']) {

            case 1:
                header("Location: /kaiadmin-lite-1.2.0/admin/dashboard.php");
                break;

            case 2:
                header("Location: /kaiadmin-lite-1.2.0/manager/dashboard.php");
                break;

            case 3:
                header("Location: /kaiadmin-lite-1.2.0/staff/dashboard.php");
                break;

            default:
                header("Location: ../login.php");
                break;
        }

        /* =========================================================
        END SUCCESS LOGIN FLOW
        ========================================================= */
        exit;

    } else {

        /* =========================================================
           PASSWORD SALAH
           =========================================================
           FLOW:
           password tidak cocok
                ↓
           set error
                ↓
           kembali ke login
        ========================================================= */
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: ../login.php");
        exit;
    }

} else {

    /* =========================================================
       USERNAME TIDAK DITEMUKAN
       =========================================================
       FLOW:
       username tidak ada di database
            ↓
       set error
            ↓
       kembali ke login
    ========================================================= */
    $_SESSION['error'] = "Invalid username or password.";
    header("Location: ../login.php");
    exit;
}

/* =========================================================
   END PROGRAM
========================================================= */