<?php

namespace estvoyage\statsd\tests\units\probe;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd\world as statsd
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
		;
	}

	function testUseMetric()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = new metric\bucket(uniqid()),
				$this->function->microtime[1] = $start = 1418733215.0566,
				$this->function->microtime[2] = $stop = 1418733220.6586
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->useBucket($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\timing($bucket, new metric\value(($stop * 10000) - ($start * 10000))))->once
		;
	}
}
