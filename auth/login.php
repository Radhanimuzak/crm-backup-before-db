<?php
/* START: halaman login */

session_start(); /* mula session (ambil/simpan login) */


/* CEK LOGIN:
   ambil: $_SESSION['user_id']
   jika ada → hantar ke dashboard → END */
if (isset($_SESSION['user_id'])) {
    header("Location: ../admin/dashboard.php"); /* output: redirect ke admin */
    exit(); /* stop sini (end flow) */
}


/* ANTI CACHE:
   elak user back page lama */
header("Cache-Control: no-cache, no-store, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 


/* DATABASE:
   ambil dari: ../config/database.php
   hasil: connection ($conn) siap guna */
require "../config/database.php";

/* END FILE:
   tunggu user isi form login
   → hantar ke: process/login-process.php */


/* AMBIL USER "REMEMBER ME":
   ambil dari table users → simpan dalam $users */

$users = []; /* init array simpan data user */

$result = $conn->query("
    SELECT user_name, remember_password 
    FROM users 
    WHERE remember_password IS NOT NULL
"); /* query: ambil user yang pernah tick remember */

while ($row = $result->fetch_assoc()) {
    $users[] = $row; /* proses: masukkan setiap user ke array */
}

/* END:
   output = $users (array)
   digunakan untuk autofill login */
?>

<!DOCTYPE html> <!-- START: dokumen HTML5, browser mula render -->

<html lang="en"> <!-- root HTML, bahasa page = English (SEO + accessibility) -->

<head> <!-- HEAD: setting page + load CSS (tak nampak pada user) -->

    <meta charset="UTF-8"> <!-- set encoding UTF-8 (paparan teks betul) -->

    <title>Login - CRM</title> <!-- tajuk tab browser -->

    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- responsive (ikut screen device) -->


    <!-- CSS: styling luar (apply ke semua elemen HTML) -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- icon library (contoh icon mata password) -->

    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/bootstrap.min.css">
    <!-- bootstrap: layout, form, button -->

    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/plugins.min.css">
    <!-- plugin tambahan styling -->

    <link rel="stylesheet" href="/kaiadmin-lite-1.2.0/assets/css/kaiadmin.min.css">
    <!-- theme utama UI (design login/dashboard) -->

  <!-- CUSTOM STYLE: tambah/override CSS sendiri -->
<style>

    /* BODY: set background + font */
    body {
        background: #f5f7fd; /* warna background */
        font-family: 'Public Sans', sans-serif; /* font utama */
    }

    /* LOGIN CARD: kotak login */
    .login-card {
        border-radius: 18px; /* bucu bulat */
        overflow: hidden; /* elak content keluar */
    }

    /* BUTTON LOGIN: button utama */
    .btn-kai-dark {
        background: #2a2f5b; /* warna button */
        border: none; /* tiada border */
        color: #fff; /* warna teks */
        height: 48px; /* tinggi button */
        font-weight: 500; /* ketebalan font */
    }

    /* HOVER BUTTON: effect bila hover */
    .btn-kai-dark:hover {
        background: #111628; /* warna hover */
    }

    /* INPUT FIELD: samakan tinggi */
    .form-control {
        height: 48px; /* tinggi input */
    }

    /* ICON INPUT: boleh klik (eye password) */
    .input-group-text {
        cursor: pointer; /* cursor jadi pointer */
    }

    /* RESPONSIVE: untuk mobile */
    @media (max-width:768px) {
        .right-image {
            display: none; /* sembunyi gambar kanan */
        }
    }

</style>

</head> <!-- END HEAD: semua CSS selesai load -->

<!-- FLOW:
     load CSS → apply ke elemen → seterusnya masuk <body> untuk UI -->

<body> <!-- START BODY: paparan UI login -->

    <!-- CONTAINER: bungkus layout, center screen -->
    <div class="container vh-100 d-flex justify-content-center align-items-center"> <!-- full height + center -->

        <!-- CARD LOGIN: kotak utama -->
        <div class="card shadow-lg login-card" style="max-width:880px;width:100%;"> <!-- design login -->

            <!-- ROW: grid system -->
            <div class="row g-0"> <!-- susun kiri kanan -->

                <!-- KIRI: form login -->
                <div class="col-md-6 p-5"> <!-- ruang input -->

                    <h3 class="fw-bold mb-2">Login to Your Account</h3> <!-- tajuk -->

                    <p class="text-muted mb-4">
                        Enter your credentials to access your account
                    </p> <!-- deskripsi -->


                    <!-- ERROR LOGIN:
                         ambil: $_SESSION['error']
                         dari: process/login-process.php
                         flow: login gagal → set session → redirect sini -->
                    <?php if (isset($_SESSION['error'])) { ?> <!-- check error -->
                        <div class="alert alert-danger"> <!-- box merah -->
                            <?php 
                                echo $_SESSION['error']; /* papar mesej */
                                unset($_SESSION['error']); /* buang supaya tak ulang */
                            ?>
                        </div>
                    <?php } ?> <!-- END error login -->


                    <!-- SUCCESS RESET:
                         ambil: $_SESSION['forgot_success']
                         dari: process/forgot-process.php
                         flow: reset berjaya → redirect → papar sini -->
                    <?php if (isset($_SESSION['forgot_success'])) { ?> <!-- check success -->
                        <div class="alert alert-success"> <!-- box hijau -->
                            Reset password link has been sent to your email.
                        </div>
                        <?php unset($_SESSION['forgot_success']); ?> <!-- buang session -->
                    <?php } ?> <!-- END success -->


                    <!-- ERROR RESET:
                         ambil: $_SESSION['forgot_error']
                         flow: reset gagal → papar sini -->
                    <?php if (isset($_SESSION['forgot_error'])) { ?> <!-- check error -->
                        <div class="alert alert-danger"> <!-- box merah -->
                            <?php 
                                echo $_SESSION['forgot_error']; /* papar mesej */
                                unset($_SESSION['forgot_error']); /* buang session */
                            ?>
                        </div>
                    <?php } ?> <!-- END error reset -->

  <!-- FORM LOGIN: hantar ke backend -->
<form method="POST" action="process/login-process.php"> <!-- hantar → process/login-process.php (POST) -->

    <input type="hidden"
        name="redirect"
        value="<?php echo $_GET['redirect'] ?? ''; ?>"> <!-- ambil redirect dari URL → hantar -->


    <!-- USERNAME -->
    <div class="form-group mb-3"> <!-- group input -->
        <label>Username</label>

        <input list="userlist"
            name="username"
            class="form-control"
            placeholder="Enter username"
            required> <!-- $_POST['username'] -->

        <datalist id="userlist"> <!-- autofill dari $users -->
            <?php foreach ($users as $u) { ?> <!-- loop -->
                <option value="<?php echo $u['user_name']; ?>"> <!-- isi -->
            <?php } ?>
        </datalist>
    </div>


    <!-- PASSWORD -->
    <div class="form-group mb-2"> <!-- group password -->
        <label>Password</label>

        <div class="input-group"> <!-- gabung input + button -->

            <input type="password"
                name="password"
                class="form-control"
                placeholder="Enter password"
                required> <!-- $_POST['password'] -->

            <button type="button"
                class="input-group-text bg-white"
                onclick="togglePassword()"> <!-- toggle -->

                <i class="fa-solid fa-eye" id="eyeIcon"></i> <!-- icon -->
            </button>

        </div>
    </div>


    <!-- REMEMBER + FORGOT -->
    <div class="d-flex justify-content-between align-items-center mb-4"> <!-- layout kiri kanan -->

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember">
            <!-- $_POST['remember'] -->
            <label class="form-check-label">
                Remember me
            </label>
        </div>

        <a href="#"
            class="text-primary small"
            data-bs-toggle="modal"
            data-bs-target="#forgotModal">
            Forgot password?
        </a> <!-- buka modal -->

    </div>


    <!-- BUTTON LOGIN -->
    <button type="submit"
        class="btn btn-kai-dark w-100 mb-3">
        Login
    </button> <!-- submit form -->


    <!-- REGISTER -->
    <div class="text-center">
        <span class="text-muted small">
            Don't have an account?
        </span>

        <a href="register.php"
            class="text-primary fw-semibold small">
            Sign up
        </a> <!-- ke register.php -->
    </div>

</form> <!-- END FORM -->

</div> <!-- ✅ TUTUP COL KIRI (INI YANG KURANG) -->

<!-- IMAGE KANAN -->
<div class="col-md-6 right-image">
    <img src="/kaiadmin-lite-1.2.0/assets/img/logo_login.png"
        class="img-fluid h-100 w-100"
        style="object-fit:cover;"
        alt="CRM Login">
</div>


</div> <!-- END ROW -->
</div> <!-- END CARD -->
</div> <!-- END CONTAINER -->
<!-- END FLOW:
     user isi form → submit → process/login-process.php
     backend → validate → redirect dashboard / error -->


<!-- MODAL FORGOT PASSWORD: popup reset password -->
<div class="modal fade" id="forgotModal"> <!-- trigger dari klik "Forgot password?" -->

    <div class="modal-dialog"> <!-- struktur popup (saiz/posisi) -->

        <div class="modal-content"> <!-- isi modal -->

            <div class="modal-header"> <!-- header: tajuk + close -->
                <h5 class="modal-title">Forgot Password</h5> <!-- tajuk modal -->

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button> <!-- tutup modal -->
            </div>


            <div class="modal-body"> <!-- body: form reset -->

                <p class="text-muted">
                    Enter your email address and we will send you a link to reset your password.
                </p> <!-- info kepada user -->


                <!-- FORM RESET:
                     hantar → process/forgot-process.php (POST)
                     input → $_POST['email']
                     flow:
                     isi email → submit → backend check email
                     → generate token → simpan DB (users)
                     → hantar email → set session
                     → redirect balik login -->
                <form method="POST" action="process/forgot-process.php">

                    <div class="mb-3"> <!-- input email -->
                        <label>Email Address</label>

                        <input type="email"
                            name="email"
                            class="form-control"
                            placeholder="Enter your email"
                            required> <!-- $_POST['email'] -->
                    </div>


                    <button type="submit"
                        class="btn btn-kai-dark w-100">
                        Send Reset Link
                    </button> <!-- submit → backend -->

                </form> <!-- END FORM -->

            </div> <!-- END BODY -->
        </div> <!-- END CONTENT -->
    </div> <!-- END DIALOG -->
</div> <!-- END MODAL -->
<!-- END FLOW:
     klik forgot → modal buka → isi email → submit
     → process/forgot-process.php
     → backend proses → hantar email → redirect login
     → papar success / error -->

    <!-- =========================
         JS LIBRARY
         ========================= -->
    <script src="/kaiadmin-lite-1.2.0/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="/kaiadmin-lite-1.2.0/assets/js/core/bootstrap.min.js"></script>
    <script src="/kaiadmin-lite-1.2.0/assets/js/kaiadmin.min.js"></script>

     <script>
        /* =========================================================
           FUNCTION TOGGLE PASSWORD

           FUNGSI:
           Tukar visibility password (show ↔ hide)

           DIGUNAKAN OLEH:
           Button icon mata (eyeIcon) di input password

           FLOW:
           1. User klik icon mata
           2. Function ini dipanggil
           3. Sistem check:
              - Jika type = password → tukar ke text (nampak)
              - Jika type = text → tukar ke password (sembunyi)
           4. Icon juga berubah (eye ↔ eye-slash)

           OUTPUT:
           User boleh lihat / sembunyikan password
        ========================================================= */
        function togglePassword() {

            var password = document.querySelector("input[name='password']");
            var icon = document.getElementById("eyeIcon");

            if (password.type === "password") {
                password.type = "text"; // papar password
                icon.classList.replace("fa-eye", "fa-eye-slash"); // tukar icon
            } else {
                password.type = "password"; // sembunyikan password
                icon.classList.replace("fa-eye-slash", "fa-eye"); // tukar balik icon
            }
        }


        /* =========================================================
           AUTO FILL PASSWORD (REMEMBER ME)

           SUMBER DATA:
           Dari PHP:
           $users → diambil dari database (users table)

           DITUKAR KE JS:
           json_encode($users)

           STRUKTUR DATA:
           [
             { user_name: "...", remember_password: "..." }
           ]

           FLOW:
           1. Page load → data users dihantar dari PHP ke JS
           2. User mula taip username
           3. Event "input" akan trigger function ini
           4. Sistem cari username dalam array users
           5. Jika jumpa:
              → decode password (base64)
              → autofill ke input password
           6. Jika tidak jumpa:
              → kosongkan password

           TUJUAN:
           Memudahkan login (auto isi password jika pernah "remember")

           NOTE PENTING:
           remember_password disimpan dalam bentuk base64
           → decode guna atob()
        ========================================================= */
            let users = <?php echo json_encode($users); ?>;

            document.querySelector("input[name='username']").addEventListener("input", function () {

                let found = users.find(u => u.user_name === this.value);

                if (found) {
                    document.querySelector("input[name='password']").value = atob(found.remember_password);
                } else {
                    // jika username tidak dijumpai, kosongkan password
                    document.querySelector("input[name='password']").value = "";
                }
            });

    </script>


</body>
</html>

<!-- =========================================================
     TAMAT PROGRAM

     FLOW AKHIR SYSTEM LOGIN:

     1. User buka login page
     2. User isi:
        - username
        - password
     3. Klik LOGIN

     HANTAR KE:
     → process/login-process.php

     BACKEND AKAN:
     - validate user
     - set session
     - redirect ke dashboard

     -------------------------------------

     JIKA FORGOT PASSWORD:

     1. User klik "Forgot password"
     2. Isi email
     3. Submit

     HANTAR KE:
     → process/forgot-process.php

     BACKEND AKAN:
     - generate token
     - simpan ke database
     - hantar email reset

     -------------------------------------

     FLOW TAMAT:
     User berjaya login ATAU reset password
========================================================= -->