<?php
function __autoload($class_name) {
	require_once './core/'.$class_name.'.php';	
}

/**
 * Container for all others Core's classes (classes that used for connection to local databse, authentication, etc)
 * 
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class Reports {
	// TODO	
	public function __construct() {
	}
} 
?>