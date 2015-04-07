<?php
	//might want to do this in php.ini instead
	set_include_path(get_include_path() . PATH_SEPARATOR . '/usr/share/nginx/ct');
	
	require_once('framework/config/Config.php');
	require_once('framework/http/Router.php');
	
	Router::getInstance()->route();
