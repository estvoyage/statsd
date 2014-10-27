<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class metric extends \atoum
{
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

				$this->calling($connection)->writeMetricComponent = function($component, $callback) use (& $connectionAfterBucketWrited) { $callback($connectionAfterBucketWrited); },
				$connectionAfterBucketWrited = new statsd\connection,

				$this->calling($connectionAfterBucketWrited)->writeMetricComponent = function($component, $callback) use (& $connectionAfterValueWrited) { $callback($connectionAfterValueWrited); },
				$connectionAfterValueWrited = new statsd\connection
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('writeMetricComponent')->withIdenticalArguments($bucket)->once
				->mock($connectionAfterBucketWrited)->call('writeMetricComponent')->withIdenticalArguments($value)->once
				->object($connectionAfterMetricWrited)->isIdenticalTo($connectionAfterValueWrited)

				->object($this->testedInstance->writeOn($connection))->isTestedInstance
				->mock($connection)->call('writeMetricComponent')->withIdenticalArguments($bucket)->twice
				->mock($connectionAfterBucketWrited)->call('writeMetricComponent')->withIdenticalArguments($value)->twice
		;
	}
}
