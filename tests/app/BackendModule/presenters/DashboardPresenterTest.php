<?php

class DashboardPresenterTest extends PHPUnit_Framework_TestCase
{
	public function testEquals()
	{
		$this->assertEquals(1, 1);
	}

	public function testFail()
	{
		$this->assertEquals(0, 1);
	}
}