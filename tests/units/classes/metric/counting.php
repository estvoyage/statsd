<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\bucket,
	estvoyage\statsd\value,
	mock\estvoyage\statsd\world as statsd
;

class counting extends \atoum
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
				$value = rand(0, PHP_INT_MAX),
				$connection = new statsd\connection,
				$callback = function($connection) use (& $connectionAfterMetricWrited) { $connectionAfterMetricWrited = $connection; }
			)
			->if(
				$this->newTestedInstance($bucket, $value),

				$this->calling($connection)->writeData = function($data, $callback) use (& $connectionAfterBucketWrited) { $callback($connectionAfterBucketWrited); },
				$connectionAfterBucketWrited = new statsd\connection,

				$this->calling($connectionAfterBucketWrited)->writeData = function($data, $callback) use (& $connectionAfterValueWrited) { $callback($connectionAfterValueWrited); },
				$connectionAfterValueWrited = new statsd\connection
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('writeData')->withArguments(new bucket($bucket))->once
				->mock($connectionAfterBucketWrited)->call('writeData')->withArguments(new value\counting($value))->once
				->object($connectionAfterMetricWrited)->isIdenticalTo($connectionAfterValueWrited)
		;
	}
}