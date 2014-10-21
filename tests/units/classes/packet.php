<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class packet extends \atoum
{
	function testWriteOn()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$value = new statsd\value,
				$connection = new statsd\connection,
				$callback = function($connection) use (& $connectionAfterPacketWrited) { $connectionAfterPacketWrited = $connection; }
			)
			->if(
				$this->newTestedInstance($bucket, $value),

				$this->calling($bucket)->writeOn = function($connection, $callback) use (& $connectionAfterBucketWrited) { $callback($connectionAfterBucketWrited); },
				$connectionAfterBucketWrited = new statsd\connection,

				$this->calling($value)->writeOn = function($value, $callback) use (& $connectionAfterValueWrited) { $callback($connectionAfterValueWrited); },
				$connectionAfterValueWrited = new statsd\connection
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($bucket)->call('writeOn')->withArguments($connection)->once
				->mock($value)->call('writeOn')->withArguments($connectionAfterBucketWrited)->once
				->object($connectionAfterPacketWrited)->isIdenticalTo($connectionAfterValueWrited)
		;
	}
}
