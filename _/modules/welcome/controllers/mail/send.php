<?
include "class.phpmailer.php"; 
include "class.smtp.php"; 

// $mailfrom -> Email gui thu
// $mailto -> Emal nhan thu
// $nameto -> Ten Nguoi Nhan
// $namefrom -> Ten nguoi gui
// $namereplay -> Ten hien thi khi nguoi nhan mail tra loi
// $subject -> Tieu de thu
// $noidung -> No dung thu


function sendmail($mailto, $nameto, $namefrom, $namereplay, $subject, $noidung )
{
	$mail = new PHPMailer();
	$mail->IsSMTP(); // set mailer to use SMTP
	$mail->Host = "smtp.gmail.com"; // specify main and backup server
	$mail->Port = 465; // set the port to use
	$mail->SMTPAuth = true; // turn on SMTP authentication
	$mail->SMTPSecure = 'ssl';
	$mail->Username = "test@e-ctn.com"; // your SMTP username or your gmail username// Email gui thu
	$mail->Password = "test123456"; // your SMTP password or your gmail password
	//$from = $mailfrom; // Reply to this email// Email khi reply
    $from = $mail->Username; // Reply to this email// Email khi reply
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






?>