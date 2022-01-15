<?php

namespace Inc\Services;

/**
 * Mail Service .
 */
class MailService
{

    public function sendMail($to, $subject, $body)
    {
        $mailHost = getenv('MAIL_HOST');
        $mailSent = 0;
        $from = getenv('MAIL_USERNAME');
        $fromName = 'Vanilla PHP';
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->CharSet = "UTF-8";
        $mail->SMTPSecure = 'tls';
        $mail->Host = $mailHost;
        $mail->Port = getenv('MAIL_PORT');
        $mail->Username = getenv('MAIL_USERNAME');
        $mail->Password = getenv('MAIL_PASSWORD');
        $mail->From = $from;
        $mail->FromName = $fromName;
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        // $mail->SMTPDebug = 2; 
        if ($mail->send()) {
            $mailSent = 1;
        }
        return $mailSent;
    }
}
