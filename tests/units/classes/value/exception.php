<?php

namespace seshat\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\value\exception')
			->extends('invalidArgumentException')
		;
	}
}
