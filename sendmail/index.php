<?php
/**
 * Simple Newsletter System
 * 
 * Version 1.8
 * 
 * Author: David Baker
 * Ver 1.5 Date: 15/Aug/2009
 * Ver 1.6 Date: 8/Nov/2009
 * Ver 1.7 Date: 24/Nov/2009 
 * Ver 1.8 Date: 1/Apr/2010
 * 
 */
if (version_compare(PHP_VERSION, '5.0.0', '<')) {
    echo "I'm sorry, PHP version 5 is needed to run this website. <br>";
    echo "The current PHP version is: " . phpversion() . "<br>";
    echo "Your web hosting provider can usually push a button to upgrade you, please contact them.";
    exit;
}

define("_NEWSLETTER_VERSION", 1.8);

session_start();

header('Content-Type: text/html; charset=UTF-8');

ob_start();
/* so we can header:redirect later on */

if (is_file("config.php")) {
    require_once("config.php");
}
require_once("php/functions.php");
require_once("php/class.newsletter.php");
$newsletter = new newsletter();

if (defined("_DB_NAME")) {

    require_once("php/database.php");

    $db = db_connect();

    if ($_REQUEST['p'] != 'setup') {
        $newsletter->init();
        require_once("php/auth.php");
    }
}

$show_menu = (isset($_REQUEST['hide_menu'])) ? false : true;

$newsletter_con = $newsletter->get_settings($db);
//echo "<pre>";print_r($newsletter_con);exit;
define("_MAIL_SMTP_HOST",$newsletter_con['_MAIL_SMTP_HOST']);
define("_MAIL_SMTP_PORT",$newsletter_con['_MAIL_SMTP_PORT']);
define("_MAIL_SMTP_SECURE",$newsletter_con['_MAIL_SMTP_SECURE']);
define("_MAIL_SMTP_AUTH",true);
define("_MAIL_SMTP_USER",$newsletter_con['_MAIL_SMTP_USER']);
define("_MAIL_SMTP_PASS",$newsletter_con['_MAIL_SMTP_PASS']);

ob_start();
?>
<div class="grid_12">
    <!-- start header -->
    <div class="header">
        <!-- start logo -->
        <div class="logo">
            <a href="?p=home"><img src="images/logo.png" /></a>
        </div>
        <!-- end logo -->
        <!-- Start nav_right_top -->
        <div class="nav_right_top">
        <?php
        $url = explode("sendmail",$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$baseURL = $url[0];
		?>
        <a style="margin-right:0px; text-decoration:none;" class="btn btn-primary navbar-right" href="http://<?php echo $baseURL;?>">
		BACK TO BACKEND</a>
            <!--<span class="fr_flag"><a href="#"><img src="images/France_flag_icon.png" /></a></span>
            <span class="en_flag"><a href="#"><img src="images/en_flag_icon.png" /></a></span>
            <span class="vn_flag"><a href="#"><img src="images/vn_flag_icon.png" /></a></span>-->
           <!-- <span class="user_icon"><a href="#"><img src="images/user_icon.png" /></a></span>-->
            <!--span><a href="#" class="icon-lock"></a></span-->
            
        </div>
        <!-- End nav_right_top --> 

        <!-- start registration menu -->
        <div class="reg_right"></div>
        <!-- end registration menu -->
        <div class="line_header"></div>
    </div>
    <!-- end header -->
    <div class="left">
        <?php if (defined("_DB_NAME")) { ?>
            <?php if ($show_menu) { ?>
                <ul>
                    <li><span class="icon-globe"></span><a href="?p=home"><?php echo _l('Dashboard'); ?></a></li>
                    <li><span class="icon-pencil "></span><a href="?p=create"><?php echo _l('Create Newsletter'); ?></a></li>
                    <li><span class="icon-time"></span><a href="?p=past"><?php echo _l('Past Newsletters'); ?></a></li>
                    <li><span class="icon-globe"></span><a href="?p=campaign"><?php echo _l('Campaigns'); ?></a></li>			
                    <li><span class="icon-cogs"></span><a href="?p=settings"><?php echo _l('Settings'); ?></a></li>			
                    <li><span class="icon-list-ul"></span><a href="?p=members"><?php echo _l('Member List'); ?></a></li>
                    <li><span class="icon-globe"></span><a href="?p=member_field"><?php echo _l('Member Field'); ?></a></li>
                    <!--li><span class="icon-user"></span><a href="?p=members_add"><?php echo _l('Add Members'); ?></a></li-->                        
                    <li><span class="icon-th-list"></span><a href="?p=category_list"><?php echo _l('Categories'); ?></a></li> 
                    <li><span class="icon-group"></span><a href="?p=groups"><?php echo _l('Groups'); ?></a></li>
                    <!--<li><span class="icon-pencil"></span><a href="http://localhost/upmd/backend/article"><?php echo _l('Article'); ?></a></li>-->
                    <li><span class="icon-user"></span><a href="?logout"><?php echo _l('Logout'); ?></a></li>
                </ul>
                <?php
            }
            ?>
        </div>
        <?php
        $page = false;
        if (isset($_REQUEST['p'])) {
            $page = basename($_REQUEST['p']);
			
        }
        if (!$page || !is_file("php/pages/" . $page . ".php")) {
            $page = "home";
        }
        include("php/pages/" . $page . ".php");
    } else {
        include("php/pages/setup.php");
    }
    ?>
</div>
<?php
$inner_content = ob_get_clean();
/* basic header split out so people can keep configuration between versions */
include("layout/system_header.php");
echo $inner_content;

include("layout/system_footer.php");
?>
