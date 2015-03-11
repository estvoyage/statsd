<?php

namespace estvoyage\statsd\tests\units\probe;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class timer extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\provider')
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

	function testStatsdMetricTemplateIsWithNoNewBucket()
	{
		$this
			->given(
				$this->function->microtime = 1,
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
		;
	}

	function testStatsdMetricTemplateIsWithNewBucket()
	{
		$this
			->given(
				$this->function->microtime = 1,
				$factory = new mockOfStatsd\metric\factory
			)
			->if(
				$bucket = new metric\bucket(uniqid()),
				$this->function->microtime[2] = $atBucket = 3,

				$this->newTestedInstance
					->newBucket($bucket)
						->statsdMetricFactoryIs($factory)
			)
			->then
				->mock($factory)
					->receive('newStatsdMetric')
						->withArguments((new metric\packet)->newMetric(new metric\timing($bucket, new metric\value(20000))))
							->once
		;
	}
}
