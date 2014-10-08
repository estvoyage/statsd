<?php

namespace seshat\statsd\tests\units\connection\socket;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\connection\socket\exception')
			->implements('seshat\statsd\world\exception')
			->extends('runtimeException')
		;
	}
}
