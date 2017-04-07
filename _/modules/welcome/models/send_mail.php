<?php
class Send_mail extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    
    public function sendmail($mailfrom, $mailto, $nameto, $namefrom, $namereplay, $subject, $noidung )
    {
        require_once "class.phpmailer.php"; 
        require_once "class.smtp.php"; 
        
    	$mail = new PHPMailer();
    	$mail->IsSMTP(); // set mailer to use SMTP
    	$mail->Host = "auth.smtp.1and1.fr"; // specify main and backup server
    	$mail->Port = 587; // set the port to use
    	$mail->SMTPAuth = true; // turn on SMTP authentication
    	$mail->SMTPSecure = 'tls';
    	//$mail->SMTPSecure = 'tls';
    	$mail->Username = "ifrcnews@ifrc.fr"; // your SMTP username or your gmail username// Email gui thu
    	//$mail->Username = "hongtien510@taowebsite.net"; // your SMTP username or your gmail username// Email gui thu
    	$mail->Password = "welcome"; // your SMTP password or your gmail password
    	$from = $mailfrom; // Reply to this email// Email khi reply
    	$to= $mailto; // Recipients email ID // Email nguoi nhan
    	$name= $nameto; // Recipient's name // Ten cua nguoi nhan
    	$mail->From = $from;
    	$mail->FromName = $namefrom; // Name to indicate where the email came from when the recepient received// Ten nguoi gui
    	$mail->AddAddress($to,$name);
    	$mail->AddReplyTo($from,$namereplay);// Ten trong tieu de khi tra loi
    	$mail->WordWrap = 50; // set word wrap
    	$mail->IsHTML(true); // send as HTML
    	$mail->Subject = $subject;
    	$mail->Body = $noidung; //HTML Body
    	$mail->AltBody = ""; //Text Body
    	//$mail->SMTPDebug = 2;
    
    	if(!$mail->Send())
    	{
    		return 0;
    	}
    	else
    	{
    		return 1;
    	}	
    }
    
}