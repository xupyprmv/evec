<?php
session_start(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>EVE economy: tool for traders </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php
function __autoload($class_name) {
	if (strpos($class_name, "C")===0) {
		require_once './core/'.$class_name.'.php';
	} else if (strpos($class_name, "L")===0) {
		require_once './leechs/'.$class_name.'.php';	
	} else if (strpos($class_name, "R")===0) {
		require_once './reports/'.$class_name.'.php';	
	}	
}
if (!CAuthentication::isAuthenticatied() && !CAuthentication::login()) {
	CAuthentication::viewLoginPage();
} else {	
	Core::install();
	$leech = new Leechs();
	$status = $leech->getServerStatus();
	echo ('Vesrion : '.$status['@attributes']['version'].'<br/>');
	echo ('Current time : '.$status['currentTime'].'<br/>');
	echo ('Cache time : '.$status['cachedUntil'].'<br/>');
	echo ('Server status : '.((strtolower($status['result']['serverOpen'])=='true')?'active':'downtime').'<br/>');
	echo ('Online players : '.$status['result']['onlinePlayers'].'<br/>');
}
?>
</body>
</html>