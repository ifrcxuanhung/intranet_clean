<?php
/*define("_DB_NAME","upmd_sendmail");
define("_DB_USER","local");
define("_DB_PASS","ifrcvn");
define("_DB_SERVER","local");

define("_DB_NAME_WEB","upmd");
define("_DB_USER_WEB","local");
define("_DB_PASS_WEB","ifrcvn");
define("_DB_SERVER_WEB","local");*/

define("_DB_NAME","sendmail_clean");
define("_DB_USER","local");
define("_DB_PASS","ifrcvn");
define("_DB_SERVER","local");

define("_DB_NAME_WEB","intranet_clean");
define("_DB_USER_WEB","local");
define("_DB_PASS_WEB","ifrcvn");
define("_DB_SERVER_WEB","local");

define("_TEMPLATE_DIR","templates/");
define("_NEWSLETTERS_DIR","newsletters/");
define("_IMAGES_DIR","images/");
define("_MAIL_SMTP",true); 
//define("_MAIL_SMTP_HOST","smtp.gmail.com");

/*define("_MAIL_SMTP_HOST","mail.ifrc.vn");
define("_MAIL_SMTP_PORT",465);
define("_MAIL_SMTP_SECURE","ssl");
define("_MAIL_SMTP_AUTH",true); 
define("_MAIL_SMTP_USER","news@upmd.fr");
define("_MAIL_SMTP_PASS","DmPuswen");*/

//define("_MAIL_SMTP_USER","service@ifrc.fr");
//define("_MAIL_SMTP_PASS","ifrcvn2013");



define("_DEBUG_MODE",false);
define("_DEMO_MODE",false);
ini_set("display_errors",_DEBUG_MODE);

// date format for printing dates to the screen (uses php date syntax)
define("_DATE_FORMAT","d/m/Y"); 
// date format for inputting dates into the system
// 1 = DD/MM/YYYY
// 2 = YYYY/MM/DD
// 3 = MM/DD/YYYY
define("_DATE_INPUT",1); 
switch(_DATE_INPUT){
	case 1: define('_DATE_INPUT_HELP','DD/MM/YYYY'); break;
	case 2: define('_DATE_INPUT_HELP','YYYY/MM/DD'); break;
	case 3: define('_DATE_INPUT_HELP','MM/DD/YYYY'); break;
}

define('HOST','http://'.$_SERVER['HTTP_HOST']);
define('BASE_URL',HOST.dirname($_SERVER['PHP_SELF']).'/');
define('PARENT_URL', str_replace('sendmail/', '', BASE_URL));