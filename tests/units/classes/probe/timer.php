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
			->implements('estvoyage\statsd\probe')
		;
	}

	function testStatsdClientIs()
	{
		$this
			->given(
				$client = new mockOfStatsd\client,
				$this->function->microtime = 1
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
				$this->function->microtime[3] = $atBucket = 3
			)
			->if(
				$this->newTestedInstance->newStatsdBucket($bucket)->statsdClientIs($client)
			)
			->then
				->mock($client)
					->receive('newStatsdMetric')
						->withArguments((new metric\packet)->newStatsdMetric(new metric\timing($bucket, new metric\value(20000))))
							->once
		;
	}
}
