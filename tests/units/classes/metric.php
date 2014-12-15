<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd,
	estvoyage\net\socket
;

class metric extends test
{
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
				$bucket = new statsd\bucket(uniqid()),
				$value = new statsd\value\counting(rand(1, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value);
		;
	}
}
