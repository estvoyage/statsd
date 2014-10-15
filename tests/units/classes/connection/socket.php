<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world\connection
;

class socket extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\socket')
		;
	}

	function testSend()
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
				->object($this->testedInstance->send($data, $host, $port))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, null)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->once

				->object($this->testedInstance->send($data, $host, $port))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, null)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->twice

			->if(
				$this->newTestedInstance($writer)
			)
			->then
				->object($this->testedInstance->send($data, $host, $port, $timeout = uniqid()))->isTestedInstance
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, $timeout)->once
				->mock($writer)->call('writeOnResource')->withIdenticalArguments($data, $resource)->thrice

			->if(
				$this->newTestedInstance($writer),
				$errorString = uniqid(),
				$this->function->fsockopen = function($host, $port, & $errno, & $error) use ($errorString) { $error = $errorString; return false; }
			)
			->then
				->exception(function() use ($data, $host, $port) { $this->testedInstance->send($data, $host, $port); })
					->isInstanceOf('estvoyage\statsd\connection\socket\exception')
					->hasMessage('Unable to connect on host \'' . $host . '\' on port \'' . $port . '\': ' . $errorString)
		;
	}
}
