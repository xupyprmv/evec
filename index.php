<?php
session_start(); 
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>EVE economy: tool for traders </title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" type="text/css" href="evec.css" />
	<link rel="icon" href="favicon.ico">
</head>
<body>
<div class="infoIcon"><img alt="Info" src="img/infoIcon.png" title="About EVEC"></div>
<div class="header">EVE economy: tool for traders</div>
<?php
function __autoload($class_name) {
	if (strpos($class_name, "C")===0) {
		require_once './core/'.$class_name.'.php';
	} else if (strpos($class_name, "E")===0) {
		require_once './eveapi/'.$class_name.'.php';	
	} else if (strpos($class_name, "R")===0) {
		require_once './reports/'.$class_name.'.php';	
	}	
}
if (CAuthentication::isAuthenticatied() || CAuthentication::login()) {
	Core::viewMainPage();
}
?>
</body>
</html>