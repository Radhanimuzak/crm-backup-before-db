<?php

use PHPMailer\PHPMailer\PHPMailer;   /* import class utama untuk hantar email */
use PHPMailer\PHPMailer\Exception;   /* import class untuk handle error */


require __DIR__.'/mail/PHPMailer.php';   /* ambil file dari folder /mail/ → library PHPMailer */
require __DIR__.'/mail/SMTP.php';        /* ambil config SMTP → untuk connect ke server email */
require __DIR__.'/mail/Exception.php';   /* ambil handler error → jika gagal hantar email */


function sendMail($to,$subject,$body){   /* function ini dipanggil dari file lain (contoh: process/register-process.php)
                                            INPUT:
                                            - $to      → email user (biasanya dari form / database)
                                            - $subject → tajuk email (ditentukan di file pemanggil)
                                            - $body    → isi email (HTML content, dari file pemanggil)
                                            FLOW:
                                            file lain → panggil function ini → email dihantar */

    $mail = new PHPMailer(true);        /* create object PHPMailer → start proses email */


    $mail->isSMTP();                   /* set mode SMTP → email akan dihantar melalui server (bukan local mail) */
    $mail->Host = 'smtp.gmail.com';    /* arah ke server Gmail SMTP → laluan keluar email */
    $mail->SMTPAuth = true;            /* aktifkan login authentication ke Gmail */


    $mail->Username = 'heitayo3r@gmail.com';   /* email penghantar (SYSTEM EMAIL)
                                                  SUMBER: hardcode dalam system
                                                  FUNGSI: akaun yang digunakan untuk hantar email */

    $mail->Password = 'molcxqytfipokejm';      /* password untuk login SMTP
                                                  SUMBER: App Password dari Gmail (BUKAN password biasa)
                                                  CARA DAPAT:
                                                  - pergi Google Account
                                                  - Security → App Password
                                                  - generate untuk SMTP
                                                  FUNGSI: bagi akses sistem untuk hantar email tanpa login manual */


    $mail->SMTPSecure = 'tls';         /* jenis security encryption → lindungi data semasa hantar */
    $mail->Port = 587;                 /* port Gmail SMTP → pintu keluar ke server email */


    $mail->setFrom('heitayo3r@gmail.com','CRM System');  /* set siapa penghantar email
                                                            email → sama seperti Username
                                                            nama  → nama sistem yang user nampak */


    $mail->addAddress($to);            /* set penerima email
                                          SUMBER: $to dari function parameter
                                          - input form user
                                          - atau database user */


    $mail->isHTML(true);               /* set format email → boleh guna HTML */
    $mail->Subject = $subject;         /* tajuk email → dari file pemanggil */
    $mail->Body = $body;               /* isi email → dari file pemanggil (boleh ada link reset password dll) */


    $mail->send();                    /* PROSES AKHIR:
                                         system → connect Gmail SMTP → hantar email → masuk inbox user */

}
?>