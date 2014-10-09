<?php

namespace estvoyage\statsd\tests\units\value\sampling;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\sampling\exception')
			->extends('invalidArgumentException')
		;
	}
}
