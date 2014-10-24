<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class client extends \atoum
{
	function testSend()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$packet = new statsd\packet
			)
			->if(
				$this->newTestedInstance($connection)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($packet)->call('writeOn')->withIdenticalArguments($connection)->once
		;
	}

	function testSendTiming()
	{
		$this
			->given(
				$connection = new statsd\connection,
				$this->calling($connection)->startPacket = function($callback) use (& $connectionStarted) { $callback($connectionStarted); },

				$connectionStarted = new statsd\connection,
				$this->calling($connectionStarted)->write = function($data, $callback) use (& $connectionWithBucketWrited) { $callback($connectionWithBucketWrited); },

				$connectionWithBucketWrited = new statsd\connection,
				$this->calling($connectionWithBucketWrited)->write = function($data, $callback) use (& $connectionWithValueWrited) { $callback($connectionWithValueWrited); },

				$connectionWithValueWrited = new statsd\connection,
				$this->calling($connectionWithValueWrited)->write = function($data, $callback) use (& $connectionWithSamplingWrited) { $callback($connectionWithSamplingWrited); },

				$connectionWithSamplingWrited = new statsd\connection,

				$this->newTestedInstance($connection)
			)
			->if(
				$bucket = uniqid(),
				$timing = rand(0, PHP_INT_MAX)
			)
			->then
				->object($this->testedInstance->sendTiming($bucket, $timing))->isTestedInstance
				->mock($connection)->call('startPacket')->once
				->mock($connectionStarted)->call('write')->withIdenticalArguments($bucket . ':')->once
				->mock($connectionWithBucketWrited)->call('write')->withIdenticalArguments($timing . '|t')->once
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('')->once
				->mock($connectionWithSamplingWrited)->call('endPacket')->once

			->if(
				$sampling = 0.1
			)
			->then
				->object($this->testedInstance->sendTiming($bucket, $timing, $sampling))->isTestedInstance
				->mock($connection)->call('startPacket')->twice
				->mock($connectionStarted)->call('write')->withIdenticalArguments($bucket . ':')->twice
				->mock($connectionWithBucketWrited)->call('write')->withIdenticalArguments($timing . '|t')->twice
				->mock($connectionWithValueWrited)->call('write')->withIdenticalArguments('|@0.1')->once
				->mock($connectionWithSamplingWrited)->call('endPacket')->twice
		;
	}
}
