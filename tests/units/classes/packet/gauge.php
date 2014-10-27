<?php

namespace estvoyage\statsd\tests\units\packet;

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
			->implements('estvoyage\statsd\world\packet')
			->extends('estvoyage\statsd\packet')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = rand(-PHP_INT_MAX, PHP_INT_MAX),
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
				->mock($connection)->call('writePacketComponent')->withArguments(new bucket($bucket))->once
				->mock($connectionAfterBucketWrited)->call('writePacketComponent')->withArguments(new value\gauge($value))->once
				->object($connectionAfterPacketWrited)->isIdenticalTo($connectionAfterValueWrited)
		;
	}
}
