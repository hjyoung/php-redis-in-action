<?php

namespace phpRedis;

class Client
{
	private $redis;

	public function __construct($config)
	{
		$redis = new Redis();

		if($redis->connect($config['host'],$config['port'],$config['timeout']))
		{
			$this->redis = $redis;
		}
		else
		{
			throw new RedisException("Couldn't connect to host");
		}
	}	

	public function close()
	{
		$this->redis->close();
	}
}
