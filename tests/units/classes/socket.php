<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\world as statsd
;

class socket extends \atoum
{
	function test__construct()
	{
		$this
			->given(
				$this->function->socket_create = uniqid(),
				$this->function->socket_last_error = $errorCode = uniqid(),
				$this->function->socket_strerror = $errorString = uniqid(),
				$this->function->socket_close->doesNothing
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->function('socket_create')->wasCalledWithArguments(AF_INET, SOCK_DGRAM, SOL_UDP)->once

			->if(
				$this->function->socket_create = false
			)
			->then
				->exception(function() { $this->newTestedInstance; })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage($errorString)
				->function('socket_last_error')->wasCalledWithArguments(null)->once
				->function('socket_strerror')->wasCalledWithArguments($errorCode)->once
		;
	}

	function test__destruct()
	{
		$this
			->given(
				$this->function->socket_create = $resource = uniqid(),
				$this->function->socket_close->doesNothing
			)
			->if(
				$this->newTestedInstance->__destruct()
			)
			->then
				->function('socket_close')->wasCalledWithArguments($resource)->once
		;
	}

	function test__clone()
	{
		$this
			->given(
				$this->function->socket_create[1] = $resource = uniqid(),
				$this->function->socket_create[2] = $clone = uniqid(),
				$this->function->socket_close->doesNothing
			)
			->if(
				$this->newTestedInstance
			)
			->when(function() { $clone = clone $this->testedInstance; })
			->then
				->function('socket_close')->wasCalledWithArguments($resource)->never
				->function('socket_close')->wasCalledWithArguments($clone)->once
		;
	}

	function testOpen()
	{
		$this
			->given(
				$host = uniqid(),
				$port = uniqid(),
				$this->function->socket_create = uniqid(),
				$this->function->socket_close->doesNothing
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->open($host, $port))
					->isInstanceOf($this->testedInstance)
					->isNotTestedInstance
				->function('socket_create')->wasCalledWithArguments(AF_INET, SOCK_DGRAM, SOL_UDP)->twice

			->if(
				$this->newTestedInstance($host, $port)
			)
			->then
				->object($this->testedInstance->open($host, $port))->isTestedInstance
				->function('socket_create')->wasCalledWithArguments(AF_INET, SOCK_DGRAM, SOL_UDP)->thrice
		;
	}

	function testWrite()
	{
		$this
			->given(
				$host = uniqid(),
				$port = uniqid(),
				$data = uniqid(),
				$this->function->socket_create = $resource = uniqid(),
				$this->function->socket_sendto = function($resource, $data) { return strlen($data); },
				$this->function->socket_close->doesNothing,
				$this->function->socket_last_error = $errorCode = uniqid(),
				$this->function->socket_strerror = $errorString = uniqid()
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->write($data))->isTestedInstance
				->function('socket_sendto')->wasCalledWithArguments($resource, $data, strlen($data), 0, '127.0.0.1', 8125)->once

			->if(
				$this->function->socket_sendto[2] = 2
			)
			->then
				->object($this->testedInstance->write($data))->isTestedInstance
				->function('socket_sendto')
					->wasCalledWithArguments($resource, $data, strlen($data), 0, '127.0.0.1', 8125)->twice
					->wasCalledWithArguments($resource, substr($data, 2), strlen($data) - 2, 0, '127.0.0.1', 8125)->once

			->if(
				$this->testedInstance->open($host, $port)->write($data)
			)
			->then
				->function('socket_sendto')->wasCalledWithArguments($resource, $data, strlen($data), 0, $host, $port)->once

			->if(
				$this->function->socket_sendto = false
			)
			->then
				->exception(function() use ($data) { $this->testedInstance->write($data); })
					->isInstanceOf('estvoyage\statsd\socket\exception')
					->hasMessage($errorString)
				->function('socket_last_error')->wasCalledWithArguments($resource)->once
				->function('socket_strerror')->wasCalledWithArguments($errorCode)->once
		;
	}

	function testClose()
	{
		$this
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->close())->isTestedInstance
		;
	}
}
