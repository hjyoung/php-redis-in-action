<?php

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
	public function testFirst()
	{
		$redis = Client::getRedis();
		$this->assertEquals($redis->ping(),"+PONG");
	}
}
