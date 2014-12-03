<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd,
	estvoyage\net\socket
;

class metric extends test
{
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
				->object($this->testedInstance->data)->isEqualTo(new socket\data($bucket . ':' . $value));
		;
	}

	function testCastToString()
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
				->castToString($this->testedInstance)->isEqualTo($bucket . ':' . $value);
		;
	}
}
