<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd
;

class counting extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\statsd\metric')
		;
	}

	function testConstructor()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(- PHP_INT_MAX, PHP_INT_MAX),
				$sampling = rand(1, 100) / 1000
			)

			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo((new statsd\bucket($bucket)) . ':' . (new statsd\value\counting($value)))

			->if(
				$this->newTestedInstance($bucket, $value, $sampling)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo((new statsd\bucket($bucket)) . ':' . (new statsd\value\counting($value) . '|@' . (new statsd\value\sampling($sampling))));
		;
	}
}
