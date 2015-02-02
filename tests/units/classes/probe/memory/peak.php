<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd\world as statsd
;

class peak extends units\test
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
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_peak_usage[1] = $firstPeak = rand(2000, 3000),
				$this->function->memory_get_peak_usage[2] = $secondPeak = rand(4000, 5000)
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->bucketIs($bucket))->isTestedInstance
				->mock($client)->call('valueGoesInto')->withArguments(metric\value::gauge($firstPeak), $bucket)->once

			->if(
				$this->testedInstance->bucketIs($bucket)
			)
			->then
				->mock($client)->call('valueGoesInto')->withArguments(metric\value::gauge($secondPeak), $bucket)->once
		;
	}
}
