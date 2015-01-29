<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric
;

class timing extends units\test
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
			->extends('estvoyage\statsd\metric')
		;
	}

	function testConstructor()
	{
		$this
			->given(
				$bucket = metric\bucket::ofName(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value . '|' . new metric\type('ms'))
		;
	}

	function testFrom()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(1, PHP_INT_MAX)
			)
			->if(
				$this->newTestedInstance(metric\bucket::ofName($bucket), new metric\value($value))
			)
			->then
				->object(metric\timing::from($bucket, $value))->isEqualTo($this->testedInstance)
		;
	}
}
