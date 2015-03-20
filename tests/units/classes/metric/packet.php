<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class packet extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\client')
			->implements('estvoyage\statsd\metric')
		;
	}

	function testParentBucketIs()
	{
		$this
			->given(
				$parentBucket = new metric\bucket(uniqid())
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->parentBucketIs($parentBucket))->isTestedInstance

			->given(
				$this->calling($metric = new mockOfStatsd\metric)->parentBucketIs = $childMetric = new mockOfStatsd\metric
			)
			->if(
				$this->testedInstance->newStatsdMetric($metric)
			)
			->then
				->object($this->testedInstance->parentBucketIs($parentBucket))
					->isTestedInstance

			->given(
				$template = new mockOfStatsd\metric\template
			)
			->if(
				$this->testedInstance->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withIdenticalArguments($childMetric)
							->once
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
