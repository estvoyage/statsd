<?php

namespace estvoyage\statsd\tests\units\probe;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mock
;

class timer extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\builder')
		;
	}

	function testValueGoesInto()
	{
		$this
			->given(
				$valueCollector = new mock\metric\value\collector,
				$bucket = new metric\bucket(uniqid()),
				$this->function->microtime[1] = $start = 1418733215.0566,
				$this->function->microtime[2] = $stop = 1418733220.6586
			)
			->if(
				$this->newTestedInstance($valueCollector)
			)
			->then
				->object($this->testedInstance->bucketIs($bucket))->isTestedInstance
				->mock($valueCollector)->call('valueGoesInto')->withArguments(metric\value::timing(($stop * 10000) - ($start * 10000)), $bucket)->once
		;
	}
}
