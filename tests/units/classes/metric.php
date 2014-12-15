<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd
;

require_once 'mock/statsd/bucket.php';
require_once 'mock/statsd/value.php';

class metric extends test
{
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
				$value = new statsd\value(rand(1, PHP_INT_MAX))
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->string($this->testedInstance->asString)->isEqualTo($bucket . ':' . $value);
		;
	}
}
