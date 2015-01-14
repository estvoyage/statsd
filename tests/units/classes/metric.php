<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd
;

class metric extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/bucket.php';
		require_once 'mock/statsd/value.php';
		require_once 'mock/statsd/value/type.php';
		require_once 'mock/statsd/value/sampling.php';
	}

	function testClass()
	{
		$this->testedClass
			->isAbstract
			->extends('estvoyage\value\string')
		;
	}

	function testConstructor()
	{
		$this
			->given(
				$bucket = new statsd\bucket(uniqid()),
				$value = new statsd\value(rand(1, PHP_INT_MAX)),
				$type = new statsd\value\type(uniqid()),
				$noSampling = new statsd\value\sampling(1.),
				$sampling = new statsd\value\sampling(rand(1, 10) / 100)
			)

			->if(
				$this->newTestedInstance($bucket, $value, $type)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value . '|' . $type)

			->if(
				$this->newTestedInstance($bucket, $value, $type, $noSampling)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value . '|' . $type)

			->if(
				$this->newTestedInstance($bucket, $value, $type, $sampling)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value . '|' . $type . '|@' . $sampling)
		;
	}
}
