<?php
if (is_file("../config.php")) {
    require_once("../config.php");
}
require_once("functions.php");
require_once("class.newsletter.php");
require_once("database.php");

$db = db_connect();
$news = new newsletter;
$news->edit_del_add($db,'member_field');
