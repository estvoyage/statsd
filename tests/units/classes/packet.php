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

				$this->calling($connection)->writePacketComponent = function($component, $callback) use (& $connectionAfterBucketWrited) { $callback($connectionAfterBucketWrited); },
				$connectionAfterBucketWrited = new statsd\connection,

				$this->calling($connectionAfterBucketWrited)->writePacketComponent = function($component, $callback) use (& $connectionAfterValueWrited) { $callback($connectionAfterValueWrited); },
				$connectionAfterValueWrited = new statsd\connection
			)
			->then
				->object($this->testedInstance->writeOn($connection, $callback))->isTestedInstance
				->mock($connection)->call('writePacketComponent')->withIdenticalArguments($bucket)->once
				->mock($connectionAfterBucketWrited)->call('writePacketComponent')->withIdenticalArguments($value)->once
				->object($connectionAfterPacketWrited)->isIdenticalTo($connectionAfterValueWrited)
		;
	}
}
