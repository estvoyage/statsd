<?php

namespace seshat\statsd\tests\units\value\timing;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\value\timing\exception')
			->implements('seshat\statsd\world\value\exception')
			->extends('seshat\statsd\value\exception')
		;
	}
}
