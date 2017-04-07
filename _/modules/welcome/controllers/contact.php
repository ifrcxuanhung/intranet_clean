<?php
require('_/modules/welcome/controllers/mail/class.phpmailer.php');
require('_/modules/welcome/controllers/mail/class.smtp.php');
require('_/modules/welcome/controllers/block.php');

class Contact extends Welcome{
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $block = new Block;
        $this->load->model('article_model');
        $this->data->logo_partners = $block->logo_partners();
        $this->data->article_contact = $this->article_model->getArticleByCodeCategory('contact',0,20,'asc');
        $this->template->write_view('content', 'contact', $this->data);
        $this->template->render();
    }
    
    public function sendcontact() {
        $name = $_POST['contact_name'];
        $email = $_POST['contact_email'];
        $message = $_POST['contact_message'];
        $subject = "From Vietnam Virtual Derivatives Market, {$email}";
        $to = 'info@ifrc.fr';
        $send = $this->sendmail($to, $to, $name, $subject, $message, $name, $email);
        echo $send == 1 ? 1 : 0;
        exit();
    }
    
    public function sendmail($mailto, $nameto, $namefrom, $subject, $noidung, $namereplay, $emailreplay) {
        $mail = new PHPMailer();
        $mail->IsSMTP(); // set mailer to use SMTP
        $mail->Host = "auth.smtp.1and1.fr"; // specify main and backup server
        $mail->Port = 587; // set the port to use
        $mail->SMTPAuth = true; // turn on SMTP authentication
        $mail->SMTPSecure = "tls";
        $mail->Username = "index@ifrc.fr"; // your SMTP username or your gmail username// Email gui thu
        $mail->Password = "welcome"; // your SMTP password or your gmail password
        //$from = $mailfrom; // Reply to this email// Email khi reply
        $mail->CharSet = "utf-8";
        $from = $emailreplay; // Reply to this email// Email khi reply
        $to = $mailto; // Recipients email ID // Email nguoi nhan
        $name = $nameto; // Recipient's name // Ten cua nguoi nhan
        $mail->From = $from;
        $mail->FromName = $namefrom; // Name to indicate where the email came from when the recepient received// Ten nguoi gui
        $mail->AddAddress($to, $name);
        $mail->AddReplyTo($from, $namereplay); // Ten trong tieu de khi tra loi
        $mail->WordWrap = 50; // set word wrap
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $subject;
        $mail->Body = $noidung; //HTML Body
        $mail->AltBody = ""; //Text Body

        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }
    }
}
