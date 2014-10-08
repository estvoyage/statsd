<?php

namespace seshat\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	mock\seshat\statsd\world\packet,
	mock\seshat\statsd\world\connection
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
				$this->newTestedInstance(new connection\socket\writer)
			)
			->then
				->object($this->testedInstance->sendPacketTo($packet, $host, $port, $timeout))->isTestedInstance
				->mock($packet)->call('writeOnSocket')->withIdenticalArguments($this->testedInstance, $host, $port, $timeout)->once
		;
	}

	function testSendTo()
	{
		$this
			->given(
				$data = uniqid(),
				$host = uniqid(),
				$port = uniqid(),
				$writer = new connection\socket\writer
			)
			->if(
				$this->newTestedInstance($writer),
				$this->function->fsockopen = $resource = uniqid()
			)
			->then
				->object($this->testedInstance->sendTo($data, $host, $port))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, null)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->once

				->object($this->testedInstance->sendTo($data, $host, $port))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, null)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->twice

			->if(
				$this->newTestedInstance($writer)
			)
			->then
				->object($this->testedInstance->sendTo($data, $host, $port, $timeout = uniqid()))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, $timeout)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->thrice

			->if(
				$this->newTestedInstance($writer),
				$errorString = uniqid(),
				$this->function->fsockopen = function($host, $port, & $errno, & $error) use ($errorString) { $error = $errorString; return false; }
			)
			->then
				->exception(function() use ($data, $host, $port) { $this->testedInstance->sendTo($data, $host, $port); })
					->isInstanceOf('seshat\statsd\connection\socket\exception')
					->hasMessage('Unable to connect on host \'' . $host . '\' on port \'' . $port . '\': ' . $errorString)
		;
	}
}
