<?php

namespace estvoyage\statsd\tests\units\packet\buffer;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units
;

class exception extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\exception')
			->extends('runtimeException')
		;
	}
}