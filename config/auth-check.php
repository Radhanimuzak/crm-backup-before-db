<?php

/* =========================
   [START CODE]
   File ini dijalankan setiap page dibuka
   Fungsi:
   - Check login (session)
   - Check role (authorization)
   - Redirect user ikut akses
========================= */

/* =========================
   SESSION START
   Ambil data login dari server (session PHP)
   Sumber:
   - process login (login-process.php)
   Data digunakan:
   - user_id
   - role
========================= */
if (session_status() === PHP_SESSION_NONE) {
    session_start(); /* Start session jika belum aktif */
}

/* =========================
   ANTI CACHE
   Tujuan:
   - Elak browser simpan page
   - Supaya user tak boleh tekan BACK selepas logout
========================= */
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); /* Disable cache */
header("Pragma: no-cache"); /* Support browser lama */

/* =========================
   CEK LOGIN (AUTHENTICATION)
   Check:
   - Adakah user sudah login?
   Ambil dari:
   - $_SESSION['user_id']
   Jika TIADA:
   - redirect ke login page
========================= */
if (!isset($_SESSION['user_id'])) {

    $redirect = urlencode($_SERVER['REQUEST_URI']); 
    /* Ambil URL semasa
       Contoh:
       /admin/dashboard.php
       → supaya selepas login boleh kembali ke page asal */

    header("Location: /kaiadmin-lite-1.2.0/auth/login.php?redirect=" . $redirect);
    /* Hantar user ke:
       /auth/login.php
       + bawa parameter redirect */

    exit(); /* Hentikan semua proses */
}

/* =========================
   CEK ROLE (AUTHORIZATION)
   Tujuan:
   - Pastikan user ada hak akses page ini
   Sumber:
   - $required_role (ditentukan dalam page)
   - $_SESSION['role']
========================= */
if (isset($required_role)) {

    $role = $_SESSION['role'] ?? 0; 
    /* Ambil role user dari session
       Jika tiada → default 0 (invalid) */

    /* =========================
       VALIDASI ROLE
       Jika role TAK sama:
       → redirect ke page masing-masing
    ========================= */
    if ($role != $required_role) {

        if ($role == 1) {
            header("Location: /kaiadmin-lite-1.2.0/admin/dashboard.php");
            /* Role 1 → Admin */
            exit();
        }

        if ($role == 2) {
            header("Location: /kaiadmin-lite-1.2.0/manager/dashboard.php");
            /* Role 2 → Manager */
            exit();
        }

        if ($role == 3) {
            header("Location: /kaiadmin-lite-1.2.0/staff/dashboard.php");
            /* Role 3 → Staff */
            exit();
        }

        /* =========================
           FALLBACK
           Jika role tak dikenali
           → paksa login semula
        ========================= */
        header("Location: /kaiadmin-lite-1.2.0/auth/login.php");
        exit();
    }
}

/* =========================
   [END CODE]
   Jika sampai sini:
   - User sudah login
   - Role betul
   - Page dibenarkan akses
========================= */