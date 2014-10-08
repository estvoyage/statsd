<?php

namespace seshat\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	seshat\statsd,
	mock\seshat\statsd\world\packet,
	mock\seshat\statsd\world\connection\socket
;

class connection extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\connection')
		;
	}

	function testSend()
	{
		$this
			->given(
				$bucket = uniqid(),
				$value = uniqid(),
				$type = uniqid(),
				$sampleRate = uniqid(),
				$host = uniqid(),
				$port = uniqid(),
				$timeout = uniqid(),
				$socket = new socket
			)
			->if(
				$this->newTestedInstance($host, $port, $socket)
			)
			->then
				->object($this->testedInstance->send($bucket, $value, $type, $sampleRate))->isTestedInstance
				->mock($socket)->call('sendPacketTo')->withArguments(new statsd\packet($bucket, $value, $type, $sampleRate), $host, $port, null)->once

				->object($this->testedInstance->send($bucket, $value, $type, $sampleRate, $timeout))->isTestedInstance
				->mock($socket)->call('sendPacketTo')->withArguments(new statsd\packet($bucket, $value, $type, $sampleRate), $host, $port, $timeout)->once
		;
	}
}
