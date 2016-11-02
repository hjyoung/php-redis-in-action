<?php


class Client
{
	public static function getRedis()
	{
		$redis = new \Redis();
		$config = require 'Config.php';
		if($redis->connect($config['host'],$config['port'],$config['timeout']))
		{
			return $redis;
		}
		else
		{
			throw new RedisException("Couldn't connect to host");
		}
	}
}
