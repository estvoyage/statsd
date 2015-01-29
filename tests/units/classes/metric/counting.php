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
				$bucket = metric\bucket::ofName(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX)),
				$sampling = new metric\sampling(rand(1, 100) / 1000)
			)

			->if(
				$this->newTestedInstance($bucket)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':1|' . new metric\type('c'))

			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value . '|' . new metric\type('c'))

			->if(
				$this->newTestedInstance($bucket, $value, $sampling)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value . '|' . new metric\type('c') . '|@' . $sampling);
		;
	}
}
