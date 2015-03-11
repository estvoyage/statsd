<?php

namespace estvoyage\statsd\tests\units\metric\factory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\statsd as mockOfStatsd
;

class etsy extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\factory')
		;
	}

	function testNewStatsdMetric()
	{
		$this
			->given(
				$this->newTestedInstance(
					$dataConsumer = new mockOfData\consumer
				)
			)

			->if(
				$metric = new mockOfStatsd\metric
			)
			->then
				->object($this->testedInstance->newStatsdMetric($metric))
					->isTestedInstance
				->mock($metric)
					->receive('statsdMetricTemplateIs')
						->withArguments(new metric\template\etsy($dataConsumer))
							->once
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$provider = new mockOfStatsd\metric\provider,
				$dataConsumer = new mockOfData\consumer
			)
			->if(
				$this->newTestedInstance($dataConsumer)
			)
			->then
				->object($this->testedInstance->statsdMetricProviderIs($provider))->isTestedInstance
				->mock($provider)
					->receive('statsdMetricFactoryIs')
						->withArguments($this->testedInstance)
							->once
		;
	}
}
