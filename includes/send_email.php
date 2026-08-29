<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require __DIR__ . "/../libraries/PHPMailer/src/Exception.php";
require __DIR__ . "/../libraries/PHPMailer/src/PHPMailer.php";
require __DIR__ . "/../libraries/PHPMailer/src/SMTP.php";
require __DIR__ . "/../config/email.php";

function send_email( $receiver_email, $subject, $message, $attachment = "" ) {

    global $smtp_host;
    global $smtp_port;
    global $smtp_email;
    global $smtp_password;
    global $smtp_sender_name;

    $mail = new PHPMailer( true );

    try {

        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_email;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $smtp_port;

        $mail->setFrom(
            $smtp_email,
            $smtp_sender_name
        );

        $mail->addAddress( $receiver_email );

        $mail->Subject = $subject;
        $mail->Body = $message;

        if ( $attachment != "" ) {
            $mail->addAttachment( $attachment );
        }

        $mail->send();

        return true;

    } catch ( Exception $error ) {

        return false;
    }
}


