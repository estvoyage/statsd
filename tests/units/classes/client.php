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
		require_once 'mock/statsd/metric.php';
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

	function testValueGoesInto()
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
				->object($this->testedInstance->valueGoesInto($value, $bucket))->isTestedInstance
		;
	}

	function test__destruct()
	{
		$this
			->given(
				$connection = new mock\connection,

				$value1 = new statsd\metric\value(uniqid()),
				$bucket1 = new statsd\metric\bucket(uniqid()),

				$value2 = new statsd\metric\value(uniqid()),
				$bucket2 = new statsd\metric\bucket(uniqid()),

				$value3 = new statsd\metric\value(uniqid()),
				$bucket3 = new statsd\metric\bucket(uniqid())
			)

			->when(
				function() use ($connection) {
					new testedClass($connection);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

			->when(
				function() use ($connection, $value1, $bucket1) {
					(new testedClass($connection))->valueGoesInto($value1, $bucket1);
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket1, $value1)))->once

			->when(
				function() use ($connection, $bucket2, $value2, $bucket3,$value3) {
					(new testedClass($connection))
						->valueGoesInto($value2, $bucket2)
						->valueGoesInto($value3, $bucket3)
					;
				}
			)
			->then
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->once
		;
	}

	function testNoMoreValue()
	{
		$this
			->given(
				$connection = new mock\connection,

				$value1 = new statsd\metric\value(uniqid()),
				$bucket1 = new statsd\metric\bucket(uniqid()),

				$value2 = new statsd\metric\value(uniqid()),
				$bucket2 = new statsd\metric\bucket(uniqid()),

				$value3 = new statsd\metric\value(uniqid()),
				$bucket3 = new statsd\metric\bucket(uniqid())
			)

			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->noMoreValue())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet)->once

				->object($this->testedInstance
						->valueGoesInto($value1, $bucket1)
							->noMoreValue())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket1, $value1)))->once

				->object($this->testedInstance
						->valueGoesInto($value2, $bucket2)
							->valueGoesInto($value3, $bucket3)
								->noMoreValue())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->once

				->object($this->testedInstance
						->valueGoesInto($value2, $bucket2)
							->valueGoesInto($value3, $bucket3)
								->noMoreValue())->isTestedInstance
				->mock($connection)->call('newPacket')->withArguments(new packet(new statsd\metric($bucket2, $value2), new statsd\metric($bucket3, $value3)))->twice
		;
	}
}
