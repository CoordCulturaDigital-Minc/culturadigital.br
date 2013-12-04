<?php

if (!function_exists('add_action')){
	@include_once($GLOBALS['HTTP_ENV_VARS']['DOCUMENT_ROOT'] . "/wp-config.php");
	if (!function_exists('add_action')) {
		include_once("../../../wp-config.php");
	} else {
		return false;
	}
}

?>