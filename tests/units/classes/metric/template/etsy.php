<?php

namespace estvoyage\statsd\tests\units\metric\template;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mockOfStatsd
;

class etsy extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
		require_once 'mock/statsd/metric/sampling.php';
		require_once 'mock/net/mtu.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\template')
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
				->object($this->testedInstance->newStatsdMetric($metric))->isTestedInstance
				->mock($metric)
					->receive('statsdMetricTemplateIs')
						->withArguments($this->testedInstance)
							->once
		;
	}

	function testMtuOFStatsdMetricConsumerIs()
	{
		$this
			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer,
				$mtu = new net\mtu(36)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->mtuOfStatsdMetricConsumerIs($statsdMetricConsumer, $mtu))->isTestedInstance

			->given(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->if(
				$this->testedInstance
					->statsdCountingContainsBucketAndValueAndSampling($bucket, $value)
						->mtuOfStatsdMetricConsumerIs($statsdMetricConsumer, $mtu)
			)
			->then
				->object($this->testedInstance->mtuOfStatsdMetricConsumerIs($statsdMetricConsumer, $mtu))->isTestedInstance
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"), $this->testedInstance)
							->once

			->given(
				$otherBucket = new metric\bucket(uniqid()),
				$otherValue = new metric\value(rand(0, PHP_INT_MAX))
			)
			->if(
				$this->testedInstance
					->statsdCountingContainsBucketAndValueAndSampling($bucket, $value)
						->statsdCountingContainsBucketAndValueAndSampling($otherBucket, $otherValue)
							->mtuOfStatsdMetricConsumerIs($statsdMetricConsumer, $mtu)
								->mtuOfStatsdMetricConsumerIs($statsdMetricConsumer, $mtu)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"), $this->testedInstance)
							->twice
						->withArguments(new data\data($otherBucket . ':' . $otherValue . '|c' . "\n"), $this->testedInstance)
							->once
		;
	}

	function testStatsdCountingContainsBucketAndValueAndSampling()
	{
		$this
			->given(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdCountingContainsBucketAndValueAndSampling($bucket, $value))->isTestedInstance

			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->testedInstance
					->statsdMetricConsumerIs($statsdMetricConsumer)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"), $this->testedInstance)
							->once
			->given(
				$samplingLessThan1 = new metric\sampling(rand(0, 9) / 10)
			)
			->if(
				$this->testedInstance
					->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingLessThan1)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c|@' . $samplingLessThan1 . "\n"), $this->testedInstance)
							->once

			->given(
				$samplingEqualTo1 = new metric\sampling(1.)
			)
			->if(
				$this->testedInstance
					->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingEqualTo1)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"), $this->testedInstance)
							->twice

			->given(
				$samplingGreaterThan1 = new metric\sampling(1 + (rand(1, 9) / 10))
			)
			->if(
				$this->testedInstance
					->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingGreaterThan1)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|c|@' . $samplingGreaterThan1 . "\n"))
							->once
		;
	}

	function testStatsdTimingContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdTimingContainsBucketAndValue($bucket, $value))->isTestedInstance

			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->testedInstance
						->statsdMetricConsumerIs($statsdMetricConsumer)
							->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|ms' . "\n"))
							->once
		;
	}

	function testStatsdGaugeContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance
			)
			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdGaugeContainsBucketAndValue($bucket, $value))->isTestedInstance

			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->testedInstance
					->statsdMetricConsumerIs($statsdMetricConsumer)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|g' . "\n"), $this->testedInstance)
							->once
		;
	}

	function testStatsdGaugeUpdateContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance
			)
			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdGaugeUpdateContainsBucketAndValue($bucket, $value))->isTestedInstance

			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->testedInstance
					->statsdMetricConsumerIs($statsdMetricConsumer)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':+' . $value . '|g' . "\n"), $this->testedInstance)
							->once

			->given(
				$value = new metric\value(rand(- PHP_INT_MAX, -1))
			)
			->if(
				$this->testedInstance
					->statsdGaugeUpdateContainsBucketAndValue($bucket, $value)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':-' . $value . '|g' . "\n"), $this->testedInstance)
							->once
		;
	}

	function testStatsdSetContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance
			)
			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdSetContainsBucketAndValue($bucket, $value))->isTestedInstance

			->given(
				$statsdMetricConsumer = new mockOfStatsd\metric\consumer
			)
			->if(
				$this->testedInstance
					->statsdMetricConsumerIs($statsdMetricConsumer)
						->statsdMetricConsumerIs($statsdMetricConsumer)
			)
			->then
				->mock($statsdMetricConsumer)
					->receive('newDataFromStatsdMetricTemplate')
						->withArguments(new data\data($bucket . ':' . $value . '|s' . "\n"), $this->testedInstance)
							->once
		;
	}
}
