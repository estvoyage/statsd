<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd as mockOfStatsd
;

class packet extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\client')
			->implements('estvoyage\statsd\metric')
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
					->receive('newStatsdMetric')
						->withArguments($this->testedInstance)
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
					->receive('statsdClientIs')
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testNewStatdMetric()
	{
		$this
			->given(
				$metric = new mockOfStatsd\metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->newTestedInstance->newStatsdMetric($metric))->isTestedInstance
		;
	}

	function testStatsdMetricFactoryIs()
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
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testStatsdMetricTemplateIs()
	{
		$this
			->given(
				$template = new mockOfStatsd\metric\template
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdMetricTemplateIs($template))->isTestedInstance
				->mock($template)
					->receive('newStatsdMetric')
						->never

			->if(
				$metric = new mockOfStatsd\metric,

				$this->testedInstance
					->newStatsdMetric($metric)
						->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->once

			->if(
				$this->testedInstance->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->once

			->if(
				$this->testedInstance
					->newStatsdMetric($metric)
						->newStatsdMetric($metric)
							->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->thrice
		;
	}
}
