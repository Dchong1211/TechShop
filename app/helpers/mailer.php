<?php

require_once __DIR__ . '/../config/env.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function send($to, $subject, $body) {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = env("MAIL_HOST");
            $mail->SMTPAuth   = true;
            $mail->Username   = env("MAIL_USERNAME");
            $mail->Password   = env("MAIL_PASSWORD");
            $mail->SMTPSecure = env("MAIL_SECURE");
            $mail->Port       = env("MAIL_PORT");

            $mail->setFrom(env("MAIL_FROM"), env("MAIL_FROM_NAME"));
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            return "Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
