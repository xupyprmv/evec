<?php
function __autoload($class_name) {
	require_once './reports/'.$class_name.'.php';	
}

/**
 * Container for all others Report's classes (classes that construct statistics from database)
 * 
 * @author Vladimir Maksimenko (xupypr@xupypr.com)
 */
class Reports {
	// TODO
	public function __construct() {
	}
} 
?>