<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class counting extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
		require_once 'mock/statsd/metric/sampling.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric')
		;
	}

	function testStatsdMetricTemplateIs()
	{
		$this
			->given(
				$statsdMetricTemplate = new mockOfStatsd\metric\template
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX)),
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->object($this->testedInstance->statsdMetricTemplateIs($statsdMetricTemplate))->isTestedInstance
				->mock($statsdMetricTemplate)
					->receive('statsdCountingContainsBucketAndValueAndSampling')
						->withArguments($bucket, $value, null)
							->once

			->if(
				$sampling = new metric\sampling(0.2),
				$this->newTestedInstance($bucket, $value, $sampling)
			)
			->then
				->object($this->testedInstance->statsdMetricTemplateIs($statsdMetricTemplate))->isTestedInstance
				->mock($statsdMetricTemplate)
					->receive('statsdCountingContainsBucketAndValueAndSampling')
						->withArguments($bucket, $value, $sampling)
							->once
		;
	}
}
