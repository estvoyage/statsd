<?php

namespace estvoyage\statsd\tests\units\connection\socket\writer;

require __DIR__ . '/../../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\socket\writer\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('runtimeException')
		;
	}
}
