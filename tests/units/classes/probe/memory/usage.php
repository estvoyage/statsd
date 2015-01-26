<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd\world as statsd
;

class usage extends units\test
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

	function testUseBucket()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = metric\bucket::ofName(uniqid()),
				$this->function->memory_get_usage[1] = $start = rand(2000, 3000),
				$this->function->memory_get_usage[2] = $stop = rand(4000, 5000)
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->useBucket($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\gauge($bucket, new metric\value($stop - $start)))->once
		;
	}
}
