<?php

namespace estvoyage\statsd\tests\units\socket;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\socket\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('estvoyage\statsd\exception')
		;
	}
}
