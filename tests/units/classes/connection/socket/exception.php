<?php

namespace estvoyage\statsd\tests\units\connection\socket;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\socket\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('runtimeException')
		;
	}
}
