<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__.'/mail/PHPMailer.php';
require __DIR__.'/mail/SMTP.php';
require __DIR__.'/mail/Exception.php';

function sendMail($to,$subject,$body){

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;

$mail->Username = 'heitayo3r@gmail.com';
$mail->Password = 'molcxqytfipokejm';

$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('heitayo3r@gmail.com','CRM System');

$mail->addAddress($to);

$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body = $body;

$mail->send();

}
?>