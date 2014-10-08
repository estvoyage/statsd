<?php

namespace estvoyage\statsd\tests\units\value\timing;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\value\timing\exception')
			->implements('estvoyage\statsd\world\value\exception')
			->extends('estvoyage\statsd\value\exception')
		;
	}
}
