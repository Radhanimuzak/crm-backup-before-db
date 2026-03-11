<?php

session_start();
require "../../config/database.php";

$username = $_POST['username'];
$password = $_POST['password'];

/* PREPARED STATEMENT */

$stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 1){

    $user = $result->fetch_assoc();

    /* VERIFY PASSWORD */

    if(password_verify($password, $user['user_password'])){

        /* CHECK STATUS */

        if($user['user_status'] != 'active'){

            $_SESSION['error'] = "Akaun anda masih menunggu kelulusan admin.";
            header("Location: ../login.php");
            exit;

        }

        /* SAVE SESSION */

        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['user_name'];
        $_SESSION['role_id'] = $user['role_id'];

        /* UPDATE LAST LOGIN */

        $update = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
        $update->bind_param("i", $user['user_id']);
        $update->execute();

        /* REDIRECT BASED ROLE */

        if($user['role_id'] == 1){

            header("Location: /kaiadmin-lite-1.2.0/admin/dashboard.php");

        } elseif($user['role_id'] == 2){

            header("Location: /kaiadmin-lite-1.2.0/manager/dashboard.php");

        } elseif($user['role_id'] == 3){

            header("Location: /kaiadmin-lite-1.2.0/staff/dashboard.php");

        } else {

            $_SESSION['error'] = "Role pengguna tidak sah.";
            header("Location: ../login.php");

        }

        exit;

    } else {

        $_SESSION['error'] = "Username atau kata laluan tidak sah.";
        header("Location: ../login.php");
        exit;

    }

} else {

    $_SESSION['error'] = "Username atau kata laluan tidak sah.";
    header("Location: ../login.php");
    exit;

}