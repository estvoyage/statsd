<?php

namespace estvoyage\statsd\tests\units\metric\template;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\data,
	estvoyage\statsd\metric,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\statsd as mockOfStatsd
;

class etsy extends units\test
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
			->implements('estvoyage\statsd\metric\template')
		;
	}

	function testStatsdCountingContainsBucketAndValueAndSampling()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdCountingContainsBucketAndValueAndSampling($bucket, $value))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"))
							->once
					->receive('noMoreData')
						->once

			->if(
				$samplingLessThan1 = new metric\sampling(rand(0, 9) / 10)
			)
			->then
				->object($this->testedInstance->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingLessThan1))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|c|@' . $samplingLessThan1 . "\n"))
							->once

			->if(
				$samplingEqualTo1 = new metric\sampling(1.),
				$this->testedInstance->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingEqualTo1)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|c' . "\n"))
							->twice

			->if(
				$samplingGreaterThan1 = new metric\sampling(1 + (rand(1, 9) / 10)),
				$this->testedInstance->statsdCountingContainsBucketAndValueAndSampling($bucket, $value, $samplingGreaterThan1)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|c|@' . $samplingGreaterThan1 . "\n"))
							->once
		;
	}

	function testNewStatsdMetric()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)
			->if(
				$metric = new mockOfStatsd\metric
			)
			->then
				->object($this->testedInstance->newStatsdMetric($metric))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($dataConsumer)
					->call('noMoreData')
						->once
		;
	}

	function testStatsdTimingContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdTimingContainsBucketAndValue($bucket, $value))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|ms' . "\n"))
							->once
					->receive('noMoreData')
						->once
		;
	}

	function testStatsdGaugeContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdGaugeContainsBucketAndValue($bucket, $value))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|g' . "\n"))
							->once
					->receive('noMoreData')
						->once
		;
	}

	function testStatsdGaugeUpdateContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdGaugeUpdateContainsBucketAndValue($bucket, $value))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':+' . $value . '|g' . "\n"))
							->once
					->receive('noMoreData')
						->once

			->if(
				$value = new metric\value(rand(- PHP_INT_MAX, -1)),
				$this->testedInstance->statsdGaugeUpdateContainsBucketAndValue($bucket, $value)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':-' . $value . '|g' . "\n"))
							->once
		;
	}

	function testStatsdSetContainsBucketAndValue()
	{
		$this
			->given(
				$this->newTestedInstance($dataConsumer = new mockOfData\consumer)
			)

			->if(
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(0, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->statsdSetContainsBucketAndValue($bucket, $value))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data($bucket . ':' . $value . '|s' . "\n"))
							->once
					->receive('noMoreData')
						->once
		;
	}
}
