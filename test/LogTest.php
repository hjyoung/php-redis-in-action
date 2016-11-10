<?php

use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function setUp()
    {
        $this->config = require "src/Config.php";
        $this->redis = new Client($this->config);
    }

	public function testRecent()
	{
        $log = new Log($this->redis);
        $log->recent("hjyang","hello");
		$this->assertEquals($this->redis->lget("recent:hjyang:info",0),"hello");
	}
}
