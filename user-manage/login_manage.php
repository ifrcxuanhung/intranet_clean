<?php 
include_once('classes/login_manage.class.php'); 

$forgot = new Login;
if(isset($_POST['username'])) {
	$forgot->login();
	
}
exit();
?>