<?php

namespace estvoyage\statsd\tests\units\client;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units
;

class generic extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\client')
			->isAbstract
		;
	}
}
