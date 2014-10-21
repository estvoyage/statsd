<?php

namespace estvoyage\statsd\tests\units\port;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\port\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('estvoyage\statsd\exception')
		;
	}
}
