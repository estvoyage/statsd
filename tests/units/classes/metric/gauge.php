<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\bucket,
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class gauge extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\metric')
			->implements('estvoyage\statsd\world\connection\data')
			->extends('estvoyage\statsd\metric')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(-PHP_INT_MAX, PHP_INT_MAX),

				$this->calling($connection = new statsd\connection)->writeData = $connectionAfterBucketWrited = new statsd\connection,
				$this->calling($connectionAfterBucketWrited)->writeData = $connectionAfterValueWrited = new statsd\connection
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterValueWrited)
				->mock($connection)->call('writeData')->withArguments(new bucket($bucket))->once
				->mock($connectionAfterBucketWrited)->call('writeData')->withArguments(new value\gauge($value))->once
		;
	}
}
