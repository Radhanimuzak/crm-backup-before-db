<?php

/*
|--------------------------------------------------------------------------
| TIMEZONE SETTING
|--------------------------------------------------------------------------
| System akan mengikuti waktu Malaysia
*/

date_default_timezone_set("Asia/Kuala_Lumpur"); 
/* SET TIMEZONE
   - Ambil: string "Asia/Kuala_Lumpur"
   - Fungsi: set waktu sistem global (date(), time())
   - Arah: digunakan oleh semua file PHP dalam sistem */

/*
|--------------------------------------------------------------------------
| DATABASE CONNECTION
|--------------------------------------------------------------------------
*/

$host = "localhost";       
/* HOST DATABASE
   - Ambil: lokasi server (localhost = server sendiri)
   - Arah: dihantar ke mysqli_connect() */

$user = "root";            
/* USER DATABASE
   - Ambil: username MySQL
   - Arah: digunakan untuk login ke database */

$password = "";            
/* PASSWORD DATABASE
   - Ambil: password MySQL (kosong untuk local)
   - Arah: authentication ke database */

$database = "crm_system";  
/* NAMA DATABASE
   - Ambil: nama database "crm_system"
   - Arah: target database untuk ambil & simpan data */


$conn = mysqli_connect($host, $user, $password, $database); 
/* CONNECTION DATABASE
   - Ambil: $host, $user, $password, $database
   - Proses: cuba connect ke MySQL
   - Output: hasilkan connection ($conn)

   ARAH FLOW:
   File ini akan dipanggil oleh file lain:
   → require/include config ini
   → guna $conn untuk query database

   DIGUNAKAN UNTUK:
   → SELECT (ambil data)
   → INSERT (simpan data)
   → UPDATE (kemaskini)
   → DELETE (hapus data)
*/

/*
|--------------------------------------------------------------------------
| CONNECTION CHECK
|--------------------------------------------------------------------------
*/

if (!$conn) { 
    /* CHECK CONNECTION
       - Jika gagal connect (false) */

    die("Database connection failed: " . mysqli_connect_error()); 
    /* STOP SYSTEM
       - Ambil error dari mysqli_connect_error()
       - Papar error ke browser
       - Flow berhenti di sini (tidak terus ke file lain) */
}

?>