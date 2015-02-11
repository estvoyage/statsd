<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\client as testedClass,
	estvoyage\statsd,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd as mock
;

class client extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric.php';
		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\value\collector')
		;
	}

	function testValueGoesInto()
	{
		$this
			->given(
				$bucket = new statsd\metric\bucket(uniqid()),
				$value = new statsd\metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance(new mock\packet\collector)
			)
			->then
				->object($this->testedInstance->valueGoesInto($value, $bucket))->isTestedInstance
		;
	}

	function test__destruct()
	{
		$this
			->given(
				$packetCollector = new mock\packet\collector,

				$value1 = new statsd\metric\value(uniqid()),
				$bucket1 = new statsd\metric\bucket(uniqid()),

				$value2 = new statsd\metric\value(uniqid()),
				$bucket2 = new statsd\metric\bucket(uniqid()),

				$value3 = new statsd\metric\value(uniqid()),
				$bucket3 = new statsd\metric\bucket(uniqid())
			)

			->when(
				function() use ($packetCollector) {
					new testedClass($packetCollector);
				}
			)
			->then
				->mock($packetCollector)->call('newPacket')->withArguments(new packet)->once

			->when(
				function() use ($packetCollector, $value1, $bucket1) {
					(new testedClass($packetCollector))->valueGoesInto($value1, $bucket1);
				}
			)
			->then
				->mock($packetCollector)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket1, $value1)))->once

			->when(
				function() use ($packetCollector, $bucket2, $value2, $bucket3,$value3) {
					(new testedClass($packetCollector))
						->valueGoesInto($value2, $bucket2)
						->valueGoesInto($value3, $bucket3)
					;
				}
			)
			->then
				->mock($packetCollector)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->once
		;
	}

	function testNoMoreValue()
	{
		$this
			->given(
				$packetCollector = new mock\packet\collector,

				$value1 = new statsd\metric\value(uniqid()),
				$bucket1 = new statsd\metric\bucket(uniqid()),

				$value2 = new statsd\metric\value(uniqid()),
				$bucket2 = new statsd\metric\bucket(uniqid()),

				$value3 = new statsd\metric\value(uniqid()),
				$bucket3 = new statsd\metric\bucket(uniqid())
			)

			->if(
				$this->newTestedInstance($packetCollector)
			)
			->then
				->object($this->testedInstance->noMoreValue())->isTestedInstance
				->mock($packetCollector)->call('newPacket')->withArguments(new packet)->once

				->object($this->testedInstance
						->valueGoesInto($value1, $bucket1)
							->noMoreValue())->isTestedInstance
				->mock($packetCollector)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket1, $value1)))->once

				->object($this->testedInstance
						->valueGoesInto($value2, $bucket2)
							->valueGoesInto($value3, $bucket3)
								->noMoreValue())->isTestedInstance
				->mock($packetCollector)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->once

				->object($this->testedInstance
						->valueGoesInto($value2, $bucket2)
							->valueGoesInto($value3, $bucket3)
								->noMoreValue())->isTestedInstance
				->mock($packetCollector)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->twice
		;
	}
}
