<?php

namespace estvoyage\statsd\tests\units\host;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\host\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('estvoyage\statsd\exception')
		;
	}
}
