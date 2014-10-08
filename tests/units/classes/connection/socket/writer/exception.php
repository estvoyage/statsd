<?php

namespace seshat\statsd\tests\units\connection\socket\writer;

require __DIR__ . '/../../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\connection\socket\writer\exception')
			->implements('seshat\statsd\world\exception')
			->extends('runtimeException')
		;
	}
}
