<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Captcha extends Welcome {
	 /**
     * Generated images
     *
     * @var mixed
     */
    public $_mImage;
    function __construct() {
        parent::__construct();
		 // Start session
        if (session_id() == '') {
            session_start();
        }

        // Init session variables
        if (!isset($_SESSION['current'])) {
            $_SESSION['current'] = array();
        }
    }
    
    public function index() {
       $template = '1234567890'; $number ='';
        for ($i=0; $i<5; $i++) $number .= $template[rand(0,9)];
		 $this->session->set_userdata('captcha_feedback', "{$number}");
        //$_SESSION['current']['captcha'][$_REQUEST['cid']] = "{$number}";
        $this->_mImage = imagecreatefromgif(base_url().'assets/images/capcha/background.gif');
        $x = 15; $y = 5; $w = 32; $h = 48;
        for ($i = 0; $i < strlen($number); $i++) {
           $img = @imagecreatefromgif(base_url()."assets/images/capcha/icon_{$number[$i]}.gif");
		   
           @imagecopy($this->_mImage, $img, $x, $y + (rand(1,5)-3)*3, 0, 0, $w, $h);
           $x += $w;
           if ($img) @imagedestroy($img);
        }
		header("Pragma: public");
        header("Expires: 0");
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: pre-check=0, post-check=0, max-age=0', false);
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Type: image/gif');
        @imagegif($this->_mImage);
    }
	

}