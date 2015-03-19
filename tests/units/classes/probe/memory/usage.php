<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class usage extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\probe')
		;
	}

	function testNewStatsdBucket()
	{
		$this
			->given(
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_usage = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->newStatsdBucket($bucket))->isTestedInstance
		;
	}

	function testStatsdClientIs()
	{
		$this
			->given(
				$client = new mockOfStatsd\client,
				$this->function->memory_get_usage = $start = 100
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdClientIs($client))->isTestedInstance
				->mock($client)
					->receive('newStatsdMetric')
						->withArguments(new metric\packet)
							->once

			->given(
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_usage[3] = $atBucket = 300
			)
			->if(
				$this->newTestedInstance->newStatsdBucket($bucket)->statsdClientIs($client)
			)
			->then
				->mock($client)
					->receive('newStatsdMetric')
						->withArguments((new metric\packet)->newStatsdMetric(new metric\gauge($bucket, new metric\value(200))))
							->once
		;
	}
}
