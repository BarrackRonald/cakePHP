<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\FactoryLocator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require ROOT. '/vendor/phpmailer/phpmailer/src/Exception.php';
require ROOT. '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require ROOT. '/vendor/phpmailer/phpmailer/src/SMTP.php';

class MailComponent extends Component
{
    public function send_mail($to, $toAdmin, $subject, $message){
        $sender = "phoan434@gmail.com";

        $header = "X-Mailer: PHP/" . phpversion() . "Return-Path: $sender";

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $mail->IsSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username   = "phoan434@gmail.com";
        $mail->Password   = "H19101976";
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        $mail->SMTPOptions = array(
            'tls' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ),
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->From = $sender;
        $mail->FromName = "From Vertu Company";

        $mail->AddAddress($to);
        $mail->addBCC($toAdmin);
        $mail->IsHTML(true);
        $mail->CreateHeader($header);
        $mail->Subject = $subject;
        $mail->Body    =  $message;

        if (!$mail->Send()) {
            return true;
        } else {
            return false;
        }
    }
}
