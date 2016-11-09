<?php

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function setUp()
    {
        $this->config = require "src/Config.php";
    }

	public function testFirst()
	{
		$redis = new Client($this->config);
		$this->assertEquals($redis->ping(),"+PONG");
	}
}
