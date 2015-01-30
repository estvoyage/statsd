<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric
;

class set extends units\test
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
				$bucket = new metric\bucket(uniqid()),
				$value = new metric\value(rand(- PHP_INT_MAX, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value . '|' . new metric\type('s'))
		;
	}
}
