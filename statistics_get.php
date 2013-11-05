<?php

session_start();

define('CMS_INCLUDE', true);

chdir("../../");

/* Library settings */
define("CLASS_PATH", "source/lib/pchart/class");
define("FONT_PATH", "source/lib/pchart/fonts");

/* pChart library inclusions */
include(CLASS_PATH."/pData.class.php");
include(CLASS_PATH."/pDraw.class.php");
include(CLASS_PATH."/pImage.class.php");
 
require("source/functions.php");
require("source/class.php");

require("source/database.php");

$user = new User();

require("source/globals.php");
require("source/security_login.php");
require("source/localization.php");


define('CMS_GET', 'STATISTICS');
require("source/get/restrictions.php");

if(!isset($_GET["Type"]) || !isset($_GET["IDT"])) {
	die(_("Invalid request"));	
}
else {
	
	$type 	= mysql_real_escape_string($_GET["Type"]);
	$idt 	= intval($_GET["IDT"]);
	
	$statistics = new Statistics();
	$statistics->SetType($type, $idt);
	
}

if(isset($_GET["Year"]) && !isset($_GET["Month"]) && !isset($_GET["Day"])) {
	
	$year 	= intval($_GET["Year"]);
	$statistics->SetTime($year);
	
}

elseif(isset($_GET["Year"]) && isset($_GET["Month"]) && !isset($_GET["Day"])) {
	
	$year 	= intval($_GET["Year"]);
	$month 	= intval($_GET["Month"]);
	$statistics->SetTime($year, $month);
	
}

elseif(isset($_GET["Year"]) && isset($_GET["Month"]) && isset($_GET["Day"])) {
	
	$year 	= intval($_GET["Year"]);
	$month 	= intval($_GET["Month"]);
	$day 	= intval($_GET["Day"]);
	$statistics->SetTime($year, $month, $day);
	
}

$data = $statistics->Exec();

echo json_encode($data);

?>