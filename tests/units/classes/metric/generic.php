<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\data,
	estvoyage\statsd\metric,
	mock\estvoyage\data as mockOfData
;

class generic extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isAbstract
			->implements('estvoyage\statsd\metric')
		;
	}
}
