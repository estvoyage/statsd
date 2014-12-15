<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd,
	mock\estvoyage\statsd\world as mock
;

class client extends test
{
	function testSend()
	{
		$this
			->given(
				$packet = new statsd\packet,
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($connection)->call('send')->withIdenticalArguments($packet)->once
		;
	}

	function testSendMetric()
	{
		require_once 'mock/statsd/metric.php';

		$this
			->given(
				$metric = new \mock\estvoyage\statsd\metric,
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->sendMetric($metric))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet($metric))->once
		;
	}

	function testGauge()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->gauge($bucket, $value))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet(new statsd\metric\gauge($bucket, $value)))->once
		;
	}

	function testCouting()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$sampling = rand(1, 100) / 1000.,
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->counting($bucket, $value))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet(new statsd\metric\counting($bucket, $value)))->once

				->object($this->testedInstance->counting($bucket, $value, $sampling))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet(new statsd\metric\counting($bucket, $value, $sampling)))->once
		;
	}

	function testTiming()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->timing($bucket, $value))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet(new statsd\metric\timing($bucket, $value)))->once
		;
	}

	function testSet()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$connection = new mock\connection
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->set($bucket, $value))->isTestedInstance
				->mock($connection)->call('send')->withArguments(new statsd\packet(new statsd\metric\set($bucket, $value)))->once
		;
	}
}
