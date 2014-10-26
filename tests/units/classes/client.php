<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	mock\estvoyage\statsd\world,
	estvoyage\statsd
;

class client extends \atoum
{
	function testSend()
	{
		$this
			->given(
				$connection = new world\connection,
				$packet = new world\packet
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
				$connection = new world\connection,
				$this->calling($connection)->writePacket = function() {},

				$this->newTestedInstance($connection)
			)
			->if(
				$bucket = uniqid(),
				$timing = rand(0, PHP_INT_MAX)
			)
			->then
				->object($this->testedInstance->sendTiming($bucket, $timing))->isTestedInstance
				->mock($connection)->call('writePacket')->withArguments(new statsd\packet\timing($bucket, $timing))->once

			->if(
				$sampling = 0.1
			)
			->then
				->object($this->testedInstance->sendTiming($bucket, $timing, $sampling))->isTestedInstance
				->mock($connection)->call('writePacket')->withArguments(new statsd\packet\timing($bucket, $timing, $sampling))->once
		;
	}
}
