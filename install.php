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
Core::install();
?>