<?php

date_default_timezone_set("UTC");

spl_autoload_register(function($class){
	if($class == 'Composer\Autoload\ClassLoader') return;
	require_once str_replace("\\","/","./src/".$class.".php");
});
