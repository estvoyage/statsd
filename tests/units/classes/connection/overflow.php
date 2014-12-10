<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units
;

class overflow extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\exception')
			->extends('overflowException')
		;
	}
}
