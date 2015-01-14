<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd,
	estvoyage\statsd\metric
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
				->string($this->testedInstance->asString)->isEqualTo((new statsd\bucket($bucket)) . ':' . (new metric\value($value)) . '|' . metric\type\counting::build())

			->if(
				$this->newTestedInstance($bucket, $value, $sampling)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo((new statsd\bucket($bucket)) . ':' . (new statsd\metric\value($value) . '|' . statsd\metric\type\counting::build() . '|@' . (new statsd\metric\sampling($sampling))));
		;
	}
}
