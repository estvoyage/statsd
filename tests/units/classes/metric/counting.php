<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric
;

class counting extends units\test
{
	function beforeTestMethod($method)
	{
		require 'mock/statsd/metric/bucket.php';
		require 'mock/statsd/metric/value.php';
		require 'mock/statsd/metric/sampling.php';
	}

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
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX)),
				$sampling = new metric\sampling(rand(1, 100) / 1000)
			)

			->if(
				$this->newTestedInstance($bucket)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':1|' . metric\type\counting::build())

			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value . '|' . metric\type\counting::build())

			->if(
				$this->newTestedInstance($bucket, $value, $sampling)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value . '|' . metric\type\counting::build() . '|@' . $sampling);
		;
	}
}
