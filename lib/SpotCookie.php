<?php
require_once "settings.php";
require_once "lib/SpotDb.php";

return;

try {
	$db = new SpotDb($settings->get('db'));
	$db->connect();
} 
catch(Exception $x) {
	die("Unable to connect to database: " . $x->getMessage() . PHP_EOL);
} # catch

if(empty($_SESSION['last_visit'])) {
	if(!isset($_COOKIE['last_visit'])) {
		$_SESSION['last_visit'] = $db->getMaxMessageTime();
	} else {
		$_SESSION['last_visit'] = $_COOKIE['last_visit'];
	} # else
} # if 
	
// set cookie
setcookie('last_visit', $db->getMaxMessageTime(), time()+(86400*$settings->get('cookie_expires')), '/', $settings->get('cookie_host'));
