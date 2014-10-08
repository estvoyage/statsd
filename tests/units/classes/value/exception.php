<?php

namespace estvoyage\statsd\tests\units\value;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\exception')
			->extends('invalidArgumentException')
		;
	}
}
