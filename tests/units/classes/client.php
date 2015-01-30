<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\client as testedClass,
	estvoyage\statsd,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd\world as mock
;

class client extends test
{
	function beforeTestMethod($method)
	{
		switch ($method)
		{
			case 'test__destruct':
			case 'testNoMoreMetric':
			case 'testNewMetrics':
				require_once 'mock/statsd/metric.php';
				break;
		}

		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\world\client')
		;
	}

	function testNewCounting()
	{
		$this
			->given(
				$bucket = new statsd\metric\bucket(uniqid()),
				$value = new statsd\metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)

			->if(
				$this->newTestedInstance(new mock\connection)
			)
			->then
				->object($this->testedInstance->newCounting($bucket))->isTestedInstance
				->object($this->testedInstance->newCounting($bucket, $value))->isTestedInstance
		;
	}

	function testNewTiming()
	{
		$this
			->given(
				$bucket = new statsd\metric\bucket(uniqid()),
				$value = new statsd\metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)

			->if(
				$this->newTestedInstance(new mock\connection)
			)
			->then
				->object($this->testedInstance->newTiming($bucket, $value))->isTestedInstance
		;
	}

	function testNewMetrics()
	{
		$this
			->given(
				$metric1 = new statsd\metric(uniqid()),
				$metric2 = new statsd\metric(uniqid()),
				$metric3 = new statsd\metric(uniqid())
			)

			->if(
				$this->newTestedInstance(new mock\connection)
			)
			->then
				->object($this->testedInstance->newMetrics($metric1, $metric2))->isTestedInstance
				->object($this->testedInstance->newMetrics($metric1, $metric2, $metric3))->isTestedInstance
		;
	}

	function test__destruct()
	{
		$this
			->given(
				$connection = new mock\connection,
				$metric1 = new statsd\metric(uniqid()),
				$metric2 = new statsd\metric(uniqid()),
				$metric3 = new statsd\metric(uniqid())
			)

			->when(
				function() use ($connection) {
					new testedClass($connection);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

			->when(
				function() use ($connection, $metric1) {
					(new testedClass($connection))->newMetric($metric1);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet($metric1))->once

			->when(
				function() use ($connection, $metric2, $metric3) {
					(new testedClass($connection))->newMetrics($metric2, $metric3);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet($metric2, $metric3))->once
		;
	}

	function testNoMoreMetric()
	{
		$this
			->given(
				$connection = new mock\connection,
				$metric1 = new statsd\metric(uniqid()),
				$metric2 = new statsd\metric(uniqid()),
				$metric3 = new statsd\metric(uniqid()),
				$bucket = new statsd\metric\bucket(uniqid()),
				$value = new statsd\metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)

			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

				->object($this->testedInstance->newMetric($metric1)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet($metric1))->once

				->object($this->testedInstance->newMetrics($metric2, $metric3)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet($metric2, $metric3))->once

				->object($this->testedInstance->newMetrics($metric2, $metric3)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet($metric2, $metric3))->twice

				->object($this->testedInstance->newCounting($bucket)->noMoreMetric())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric\counting($bucket)))->once
		;
	}
}
