<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\exception')
			->extends('exception')
		;
	}
}
