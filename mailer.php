<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
// $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPSecure = "ssl";
$mail->Username = "lgusaqsys@gmail.com";
$mail->Password = "dicq lgps sbqp eskk";
$mail->SMTPSecure = "ssl";
$mail->Port = 465;

$mail->setFrom("lgusaqsys@gmail.com");

$mail->isHtml(true);

return $mail;


?>