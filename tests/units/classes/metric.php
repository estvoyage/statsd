<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class metric extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\data')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$value = new statsd\value,
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
				->mock($connection)->call('writeData')->withIdenticalArguments($bucket)->once
				->mock($connectionAfterBucketWrited)->call('writeData')->withIdenticalArguments($value)->once
				->object($connectionAfterMetricWrited)->isIdenticalTo($connectionAfterValueWrited)

				->object($this->testedInstance->writeOn($connection))->isTestedInstance
				->mock($connection)->call('writeData')->withIdenticalArguments($bucket)->twice
				->mock($connectionAfterBucketWrited)->call('writeData')->withIdenticalArguments($value)->twice
		;
	}
}
