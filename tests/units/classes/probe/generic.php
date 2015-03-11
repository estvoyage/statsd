<?php

namespace estvoyage\statsd\tests\units\probe;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class generic extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isAbstract
			->implements('estvoyage\statsd\probe')
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
		;
	}
}
