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

	function testStatsdMetricConsumerIs()
	{
		$this
			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdMetricConsumerIs($statsdMetricConsumer))
					->isTestedInstance
				->mock($statsdMetricConsumer)
					->receive('statsdMetricTemplateIs')
						->withArguments(new metric\template\etsy)
							->once
		;
	}

	function testNewStatsdMetric()
	{
		$this
			->given(
				$this->newTestedInstance
			)
			->if(
				$metric = new mockOfStatsd\metric
			)
			->then
				->object($this->testedInstance->newStatsdMetric($metric))
					->isTestedInstance
				->mock($metric)
					->receive('statsdMetricTemplateIs')
						->withArguments(new metric\template\etsy)
							->once
		;
	}

	function testStatsdMetricProviderIs()
	{
		$this
			->given(
				$provider = new mockOfStatsd\metric\provider
			)
			->if(
				$this->newTestedInstance
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
