<?php

spl_autoload_register(function($class){
	if($class == 'Composer\Autoload\ClassLoader') return;
	require_once str_replace("\\","/","/home/php-redis-in-action/src/".$class.".php");
});