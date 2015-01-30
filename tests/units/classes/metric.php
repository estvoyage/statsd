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
		require_once 'mock/statsd/metric/bucket.php';
		require_once 'mock/statsd/metric/value.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\value\string')
		;
	}

	function testConstructor()
	{
		$this
			->given(
				$bucket = new statsd\metric\bucket(uniqid()),
				$value = new statsd\metric\value(rand(1, PHP_INT_MAX))
			)

			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value)
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value)
		;
	}
}
