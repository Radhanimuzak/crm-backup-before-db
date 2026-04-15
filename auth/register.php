<?php
// =====================================================
// [MULA SISTEM]
// =====================================================
// Titik permulaan pelaksanaan fail ini apabila halaman dibuka

// =====================================================
// MULAKAN SESSION
// =====================================================
// Mengaktifkan session PHP
// Fungsi:
// - Menyimpan data sementara daripada halaman lain (backend)
// - Digunakan untuk komunikasi antara fail (redirect)

// SUMBER DATA SESSION:
// berasal daripada fail:
// ➜ process/register-process.php

// JENIS DATA YANG DITERIMA:
// - $_SESSION['register_error']   → jika pendaftaran gagal
// - $_SESSION['register_success'] → jika berjaya

session_start();
?>

<!DOCTYPE html>
<html lang="ms">
<head>

    <!-- =====================================================
         [MULA HEAD]
         Bahagian konfigurasi halaman (tiada pemprosesan data)
    ===================================================== -->

    <!-- META DATA -->
    <meta charset="UTF-8">
    <title>Daftar - CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- =====================================================
         MUATKAN CSS (SUMBER PAPARAN)
         TIADA LOGIK / ALIRAN DATA
    ===================================================== -->

    <!-- ICON (Font Awesome CDN) -->
    <!-- Diambil dari internet (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- TEMPLATE CSS (FAIL TEMPATAN PROJEK) -->
    <!-- SUMBER FAIL:
         /kaiadmin-lite-1.2.0/assets/css/
         
         Digunakan untuk gaya UI (bukan pemprosesan data)
    -->
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">

    <!-- =====================================================
         GAYA TERSUAI (HANYA PAPARAN)
         TIDAK BERKAITAN DENGAN BACKEND
    ===================================================== -->
<style>

    /* MULA STYLE
       Bahagian ini hanya untuk paparan (frontend)
       Tidak ambil data, tidak hantar data, tidak ke pangkalan data */

    /* Body: tetapkan paparan asas halaman */
    body{
        background:#f5f7fd; /* warna latar belakang */
        font-family:'Public Sans',sans-serif; /* fon */
        padding:40px 0; /* jarak atas bawah */
    }

    /* Wrapper: pusatkan borang di tengah */
    .register-wrapper{
        max-width:560px; /* had lebar */
        margin:auto; /* tengah */
    }

    /* Card: kotak borang */
    .register-card{
        border-radius:18px; /* sudut bulat */
        padding:40px; /* jarak dalam */
        border:none;
    }

    /* Tajuk seksyen */
    .section-title{
        font-size:14px;
        font-weight:600;
        color:#6c757d;
        margin-top:20px;
        margin-bottom:10px;
    }

    /* Input & select */
    .form-control,
    .form-select{
        height:48px; /* tinggi sama */
        border-radius:8px;
    }

    /* Butang daftar */
    .btn-kai-dark{
        background:#2a2f5b;
        color:#fff;
        height:48px;
        border-radius:8px;
    }

    /* Hover butang */
    .btn-kai-dark:hover{
        background:#111628;
    }

    /* Wrapper ikon input */
    .input-icon{
        position:relative;
    }

    /* Kedudukan ikon dalam input */
    .input-icon i{
        position:absolute;
        left:12px;
        top:50%;
        transform:translateY(-50%);
        color:#999;
    }

    /* Jarak teks input supaya tidak terkena ikon */
    .input-icon input{
        padding-left:36px;
    }

    /* Ikon berjaya (modal) */
        .success-icon{
            width:120px;
            height:120px;
            border:4px solid #28a745;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
        }

    /* Tanda semak berjaya */
        .success-icon::after{
            content:'';
            width:30px;
            height:60px;
            border-right:6px solid #28a745;
            border-bottom:6px solid #28a745;
            transform:rotate(45deg);
            display:block; /* 👉 penting supaya ikut flex center */
        }

    /* Gaya modal */
    .modal-content{
        border-radius:14px;
    }

    /* TAMAT STYLE
       Aliran:
       HTML → CSS ini → dipaparkan kepada pengguna
       (tidak berkaitan dengan backend sama sekali) */
</style>

</head>
    <!-- =====================================================
         [TAMAT HEAD]
    ===================================================== -->

<body>
<!-- =====================================================
     [MULA UI HALAMAN]
     Pengguna bermula dari sini (frontend)
===================================================== -->

<div class="container register-wrapper">
    <!-- WRAPPER UTAMA
         Fungsi: tempat susun atur borang (tiada pemprosesan data) -->

    <div class="card shadow register-card">
        <!-- CARD BORANG
             Fungsi: paparan kotak borang -->

        <!-- TAJUK -->
        <h3 class="fw-bold mb-2">Create Account</h3>
        <!-- Hanya paparan (tidak hantar / ambil data) -->

        <p class="text-muted mb-3">
            Fill in the information below to register your account
        </p>
        <!-- Hanya penerangan -->

        <!-- =====================================================
             SEMAK DATA DARI BACKEND (SESSION)
             
             SUMBER DATA:
             ➜ process/register-process.php

             ALIRAN:
             1. Pengguna hantar borang (di halaman ini)
             2. Data dihantar ke:
                → process/register-process.php
             3. Jika RALAT:
                → backend set:
                   $_SESSION['register_error']
             4. Backend redirect kembali ke halaman ini
             5. Kod ini menangkap & memaparkan ralat
        ===================================================== -->
        <?php if(isset($_SESSION['register_error'])){ ?>

            <!-- PAPARKAN RALAT KEPADA PENGGUNA -->
            <div class="alert alert-danger">
                <?php
                    echo $_SESSION['register_error'];
                    // Ambil mesej ralat dari session (hasil proses backend)

                    unset($_SESSION['register_error']);
                    // Padam session selepas dipaparkan
                    // Tujuan: supaya tidak muncul lagi semasa refresh
                ?>
            </div>

        <?php } ?>
        <!-- =====================================================
             [TAMAT SEMAK RALAT]
             Jika tiada ralat → bahagian ini akan dilewati
        ===================================================== -->

 <!-- =====================================================
     [FORM MULA]
     TITIK PERMULAAN PENGAMBILAN DATA DARIPADA PENGGUNA
     
     SUMBER INPUT:
     → PENGGUNA (frontend / pelayar)

     METHOD:
     → POST (data tidak dipaparkan dalam URL)

     TUJUAN HANTAR DATA:
     → process/register-process.php (fail backend)

     ALIRAN:
     Pengguna isi borang
        ↓
     Klik butang submit (nanti di bawah)
        ↓
     Semua input dihantar ke:
     process/register-process.php
===================================================== -->
<form method="POST" action="process/register-process.php">

    <!-- =========================
         MAKLUMAT AKAUN
         KUMPULAN DATA PENGGUNA
         (akan dihantar ke backend)
    ========================= -->
    <div class="section-title">Account Information</div>

    <!-- =========================
         INPUT USERNAME
         SUMBER: pengguna taip di pelayar
         
         DIHANTAR KE BACKEND:
         → $_POST['user_name']

         DIGUNAKAN DI:
         → register-process.php
    ========================= -->
    <div class="form-group mb-3 input-icon">
        <i class="fa fa-user"></i>
        <input type="text" name="user_name" class="form-control" placeholder="Username" required>
    </div>

    <!-- =========================
         INPUT NAMA PENUH
         → $_POST['user_fullname']
    ========================= -->
    <div class="form-group mb-3 input-icon">
        <i class="fa fa-id-card"></i>
        <input type="text" name="user_fullname" class="form-control" placeholder="Full Name" required>
    </div>

    <!-- =========================
         INPUT EMEL
         → $_POST['user_email']
    ========================= -->
    <div class="form-group mb-3 input-icon">
        <i class="fa fa-envelope"></i>
        <input type="email" name="user_email" class="form-control" placeholder="Email Address" required>
    </div>

    <!-- =========================
         INPUT NOMBOR TELEFON
         → $_POST['user_mobile_no']
    ========================= -->
    <div class="form-group mb-3 input-icon">
        <i class="fa fa-phone"></i>
        <input type="text" name="user_mobile_no" class="form-control" placeholder="Mobile Number" required>
    </div>
<!-- =====================================================
     [TAMAT ALIRAN BAHAGIAN INI]

     RINGKASAN ALIRAN:
     Pengguna isi input
        ↓
     Data disimpan sementara dalam pelayar
        ↓
     Apabila submit:
        ↓
     Dihantar ke:
     process/register-process.php
        ↓
     Backend akan:
     - ambil $_POST
     - sahkan data
     - simpan ke pangkalan data 
===================================================== -->

<!-- =========================
     DATA KERJA (SAMBUNGAN INPUT PENGGUNA)
     SUMBER: PENGGUNA (frontend)
     AKAN DIHANTAR KE BACKEND
========================= -->
<div class="section-title">Work Information</div>

<!-- =========================
     JAWATAN
     INPUT DARIPADA PENGGUNA
     
     DIHANTAR KE:
     → $_POST['user_position']

     DIGUNAKAN DI:
     → process/register-process.php
========================= -->
<div class="form-group mb-3 input-icon">
    <i class="fa fa-briefcase"></i>
    <input type="text" name="user_position" class="form-control" placeholder="Position" required>
</div>

<!-- =========================
     BAHAGIAN (DIVISION)
     PENGGUNA PILIH DROPDOWN
     
     NILAI YANG DIHANTAR:
     → $_POST['division_id']

     CATATAN:
     value (1,2,3,...) adalah ID daripada jadual pangkalan data (division)
========================= -->
<div class="form-group mb-3">
    <select name="division_id" class="form-select" required>
        <option value="">Select Division</option>
        <option value="1">Perkhidmatan Langganan</option>
        <option value="2">Jualan Digital</option>
        <option value="3">IT</option>
        <option value="4">HR</option>
        <option value="5">Bold</option>
        <option value="6">Khidmat Pelanggan</option>
    </select>
</div>

<!-- =========================
     UNIT
     PENGGUNA PILIH DROPDOWN
     
     DIHANTAR KE:
     → $_POST['unit_id']

     CATATAN:
     biasanya berkaitan dengan jadual unit dalam pangkalan data
========================= -->
<div class="form-group mb-4">
    <select name="unit_id" class="form-select" required>
        <option value="">Select Unit</option>
        <option value="1">Pangkalan Data</option>
        <option value="2">Jualan</option>
        <option value="3">Software Development</option>
        <option value="4">Recruitment</option>
        <option value="5">UKP</option>
    </select>
</div>

<!-- =========================
     BUTANG HANTAR (TRIGGER UTAMA)
     
     AKSI:
     PENGGUNA KLIK → BORANG DIHANTAR
     
     TUJUAN:
     → process/register-process.php
========================= -->
<button type="submit" class="btn btn-kai-dark w-100 mb-3">
    Register
</button>

<!-- =========================
     NAVIGASI
     TIDAK BERKAITAN DENGAN DATA
     HANYA PINDAH HALAMAN
     
     HALA TUJU:
     → login.php
========================= -->
<div class="text-center">
    <span class="text-muted small">
        Already have an account?
    </span>

    <a href="login.php" class="text-primary fw-semibold small">
        Login
    </a>
</div>

</form>
<!-- =====================================================
     [TAMAT BORANG / TITIK AKHIR INPUT]

     ALIRAN LENGKAP BAHAGIAN INI:

     1. Pengguna isi data (jawatan, bahagian, unit)
     2. Klik butang "Register"
     3. Semua data dihantar ke:
        → process/register-process.php
     4. Backend:
        - ambil $_POST
        - sahkan data
        - simpan ke pangkalan data
        - set SESSION (berjaya / ralat)
     5. Redirect kembali ke halaman ini
===================================================== -->

</div>
</div>
<!-- PENUTUP WRAPPER UI
     Tiada pemprosesan data
     Hanya penutup struktur paparan -->

<!-- =====================================================
     MODAL BERJAYA (POPUP)
     
     FUNGSI:
     Memaparkan mesej jika pendaftaran BERJAYA

     TIDAK MENGAMBIL DATA SECARA LANGSUNG
     Hanya dipaparkan jika dicetuskan oleh JS (di bawah)
===================================================== -->
<div class="modal fade" id="registerSuccess">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-4 d-flex flex-column align-items-center">

                <!-- IKON VISUAL -->
                <div class="success-icon"></div>

                <!-- TEKS MAKLUMAT -->
                <h4 class="fw-bold mb-2">
                    Registration Submitted
                </h4>

                <p class="text-muted">
                    Your account has been created successfully.
                    Please wait for the administrator to assign your role.
                    Once your role has been assigned, you will receive an email to create your password.
                </p>

                <!-- BUTANG TUTUP MODAL -->
                <button class="btn btn-kai-dark mt-3" data-bs-dismiss="modal">
                    Done
                </button>

            </div>
        </div>
    </div>
</div>

<!-- =====================================================
     FAIL JS (FUNGSI FRONTEND)
     
     SUMBER FAIL:
     /kaiadmin-lite-1.2.0/assets/js/

     FUNGSI:
     - jQuery → pembantu JS
     - Bootstrap → untuk modal (popup)
     - kaiadmin → UI tambahan

     TIDAK BERKAITAN DENGAN PANGKALAN DATA
===================================================== -->
<script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
<script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>

<?php
// =====================================================
// SEMAK STATUS BERJAYA DARI BACKEND
//
// SUMBER:
// process/register-process.php
//
// ALIRAN:
// 1. Pengguna hantar borang
// 2. Data dihantar ke:
//    → process/register-process.php
// 3. Jika BERJAYA:
//    → backend set:
//       $_SESSION['register_success']
// 4. Redirect kembali ke halaman ini
// 5. Bahagian ini menyemak session tersebut
// =====================================================
if(isset($_SESSION['register_success'])){
?>

<script>
    // =====================================================
    // CETUS MODAL BERJAYA
    // Jika session wujud → paparkan popup
    // =====================================================
    var myModal = new bootstrap.Modal(document.getElementById('registerSuccess'));
    myModal.show();
</script>

<?php
// =====================================================
// PADAM SESSION
// Supaya modal tidak muncul lagi semasa refresh
// =====================================================
unset($_SESSION['register_success']);
}
?>

<!-- =====================================================
     [TAMAT SISTEM]

     ALIRAN AKHIR:

     Backend (register-process.php)
         ↓
     set SESSION berjaya
         ↓
     redirect ke halaman ini
         ↓
     kod ini semak session
         ↓
     paparkan modal berjaya
         ↓
     session dipadam
         ↓
     selesai
===================================================== -->

</body>
</html>