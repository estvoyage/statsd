<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class peak extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\provider')
		;
	}

	function testNewBucket()
	{
		$this
			->given(
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_peak_usage = rand(- PHP_INT_MAX, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->newBucket($bucket))->isTestedInstance
		;
	}

	function testStatsdClientIs()
	{
		$this
			->given(
				$client = new mockOfStatsd\client
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdClientIs($client))->isTestedInstance
				->mock($client)
					->receive('statsdMetricProviderIs')
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testStatsdMetricTemplateIs()
	{
		$this
			->given(
				$factory = new mockOfStatsd\metric\factory
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdMetricFactoryIs($factory))->isTestedInstance
				->mock($factory)
					->receive('newStatsdMetric')
						->withArguments(new metric\packet)
							->once

			->if(
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_peak_usage = $peak = rand(- PHP_INT_MAX, PHP_INT_MAX),

				$this->newTestedInstance
					->newBucket($bucket)
						->statsdMetricFactoryIs($factory)
			)
			->then
				->mock($factory)
					->receive('newStatsdMetric')
						->withArguments((new metric\packet)->newMetric(new metric\gauge($bucket, new metric\value($peak))))
							->once
		;
	}
}
