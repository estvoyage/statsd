<?php

namespace estvoyage\statsd\tests\units\metric\factory\exception;

require __DIR__ . '/../../../../runner.php';

use
	estvoyage\statsd\tests\units
;

class overflow extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\metric\factory\exception')
			->extends('overflowException')
		;
	}
}
