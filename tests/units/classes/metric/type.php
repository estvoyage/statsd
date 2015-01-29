<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units
;

class type extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\value\string')
			->isFinal
		;
	}
}
