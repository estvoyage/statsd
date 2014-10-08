<?php

namespace seshat\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	mock\seshat\statsd\world\packet
;

class socket extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\connection\socket')
		;
	}

	function testSendPacketTo()
	{
		$this
			->given(
				$packet = new packet,
				$host = uniqid(),
				$port = uniqid(),
				$timeout = uniqid()
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->sendPacketTo($packet, $host, $port, $timeout))->isTestedInstance
				->mock($packet)->call('writeOnSocket')->withIdenticalArguments($this->testedInstance, $host, $port, $timeout)->once
		;
	}
}
