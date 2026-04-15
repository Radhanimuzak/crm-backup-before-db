<?php

/* =========================================================
   START: FAIL LOGOUT

   DIPANGGIL DARI:
   - Klik butang / pautan logout (contoh: logout.php)

   TUJUAN AKHIR:
   - Padam session (server)
   - Padam cookie (browser)
   - Redirect ke login.php
========================================================= */

session_start(); // Ambil session aktif dari server (data login user)

/* =========================
   PADAM SEMUA SESSION
========================= */

/* ARAH:
   Session lama (server) → dipadam → user jadi logout */
$_SESSION = [];
session_destroy();


/* =========================
   PADAM COOKIE SESSION (JIKA ADA)
========================= */

/* ARAH:
   Cookie session (browser) → dipadam (expire) */
if (ini_get("session.use_cookies")) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(), // nama cookie session
        '',
        time() - 42000, // expire → padam
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}


/* =========================
   ANTI BACK (ANTI CACHE)
========================= */

/* ARAH:
   Server → browser (jangan simpan cache supaya tak boleh back) */
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


/* =========================
   REDIRECT KE LOGIN
========================= */

/* ARAH AKHIR:
   Fail ini → pindah ke login.php (paparan login) */
header("Location: login.php");
exit();

/* END: proses tamat di login.php */

?>