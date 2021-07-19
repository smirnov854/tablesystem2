<?php
use PHPMailer\PHPMailer\PHPMailer;
class Mail_model extends CI_Model
{
    private $smtp = "smtp.yandex.ru";
    private $user_name = "smirnov_e87@telegrammbots.ru";
    private $password = "Linkoln431861";    
    /*
        private $smtp = "ssl://smtp.beget.com";
        private $user_name = "info@studyrussia.net";
        private $password = "y%cTQ3IU00";*/

    public function send(string $to, string $subject, string $body, array $attachments = []){
        include './vendor/autoload.php';
        $mail = new PHPMailer();

        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->isSMTP();
        $mail->Host = $this->smtp;
        $mail->SMTPAuth = true;
        $mail->Username = $this->user_name;
        $mail->Password = $this->password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($mail->Username);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $mail->Body = $body;
        /*
        if(!empty($attachments)){
            foreach($attachments as $row){
                $mail->addAttachment($row);
            }
        }*/

        $res = $mail->send();
       // var_dump($mail->ErrorInfo); 
        return $res;
    }
    
}