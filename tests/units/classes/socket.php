<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\world as statsd
;

class socket extends \atoum
{
	function testOpen()
	{
		$this
			->given(
				$host = uniqid(),
				$port = uniqid(),
				$callback = function($socket) use (& $openedSocket) { $openedSocket = $socket; },
				$this->function->fsockopen = uniqid()
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->open($host, $port, $callback))->isTestedInstance
				->object($openedSocket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->function('fsockopen')->wasCalledWithArguments('udp://' . $host, $port, null, null, null)->once

			->if(
				$errorString = uniqid(),
				$this->function->fsockopen = function($host, $port, & $errno, & $error) use ($errorString) { $error = $errorString; return false; }
			)
			->then
				->exception(function() use ($host, $port) { $this->testedInstance->open($host, $port, function() {}); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage('Unable to connect on host \'' . $host . '\' on port \'' . $port . '\': ' . $errorString)
		;
	}

	function testWrite()
	{
		$this
			->given(
				$data = uniqid(),
				$this->function->fsockopen = $resource = uniqid(),
				$this->function->fwrite = function($resource, $data) { return strlen($data); }
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() { $this->testedInstance->write(uniqid()); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage('Socket is not open')

			->if(
				$this->testedInstance->open(uniqid(), uniqid(), function($socket) use (& $openedSocket) { $openedSocket = $socket; })
			)
			->then
				->exception(function() { $this->testedInstance->write(uniqid()); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage('Socket is not open')
				->object($openedSocket->write($data))->isIdenticalTo($openedSocket)
				->function('fwrite')->wasCalledWithArguments($resource, $data, strlen($data))->once

			->if(
				$this->function->fwrite[2] = 2
			)
			->then
				->object($openedSocket->write($data))->isIdenticalTo($openedSocket)
				->function('fwrite')
					->wasCalledWithArguments($resource, $data, strlen($data))->twice
					->wasCalledWithArguments($resource, substr($data, 2), strlen($data) - 2)->once

			->if(
				$this->function->fwrite = false
			)
			->then
				->exception(function() use ($openedSocket, $data) { $openedSocket->write($data); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage('Unable to write \'' . $data . '\'')
		;
	}

	function testClose()
	{
		$this
			->given(
				$callback = function($socket) use (& $closedSocket) { $closedSocket = $socket; },
				$this->function->fsockopen = $resource = uniqid(),
				$this->function->fclose = true
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->close($callback))->isTestedInstance
				->object($closedSocket)->isTestedInstance

			->if(
				$this->testedInstance->open(uniqid(), uniqid(), function($socket) use (& $openedSocket) { $openedSocket = $socket; })
			)
			->then
				->object($this->testedInstance->close($callback))->isTestedInstance
				->object($closedSocket)->isTestedInstance
				->object($openedSocket->close($callback))->isIdenticalTo($openedSocket)
				->object($closedSocket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->function('fclose')->wasCalledWithArguments($resource)->once

			->if(
				$this->function->fclose = false
			)
			->then
				->object($this->testedInstance->close($callback))->isTestedInstance
				->object($closedSocket)->isTestedInstance
				->exception(function() use ($openedSocket) { $openedSocket->close(function() {}); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage('Unable to close')

		;
	}

}
