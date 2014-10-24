<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\socket,
	mock\estvoyage\statsd\world as statsd
;

class connection extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection')
		;
	}

	function test__construct()
	{
		$this
			->given(
				$address = new statsd\address,
				$this->calling($address)->openSocket = function($socket, $callback) { $callback(new statsd\socket); }
			)
			->if(
				$this->newTestedInstance($address, new statsd\connection\mtu)
			)
			->then
				->mock($address)->call('openSocket')->withArguments(new socket)->once

			->if(
				$this->calling($address)->openSocket->throw = new \exception(uniqid())
			)
			->then
				->exception(function() use ($address) { $this->newTestedInstance($address, new statsd\connection\mtu); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('Unable to open connection')
		;
	}

	function testOpen()
	{
		$this
			->given(
				$address = new statsd\address,
				$this->calling($address)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket = new statsd\socket); },
				$this->newTestedInstance($address, new statsd\connection\mtu),
				$callback = function($connection) use (& $openedConnection) { $openedConnection = $connection; }
			)
			->if(
				$otherAddress = new statsd\address,
				$this->calling($otherAddress)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket = new statsd\socket); }
			)
			->then
				->object($this->testedInstance->open($otherAddress, $callback))->isTestedInstance
				->mock($otherAddress)->call('openSocket')->withArguments(new socket)->once
				->object($openedConnection)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

			->if(
				$this->calling($otherAddress)->openSocket->throw = new \exception(uniqid())
			)
			->then
				->exception(function() use ($otherAddress) { $this->testedInstance->open($otherAddress, function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('Unable to open connection')
		;
	}

	function testStartPacket()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterStartPacket) { $connectionAfterStartPacket = $connection; },
				$mtu = new statsd\connection\mtu,
				$this->calling($mtu)->reset = function($callback) use (& $mtuAfterReset) { $callback($mtuAfterReset); }
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->startPacket($callback))->isTestedInstance
				->mock($mtu)->call('reset')->once
				->object($connectionAfterStartPacket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
		;
	}

	function testWrite()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterWrite) { $connectionAfterWrite = $connection; },
				$mtu = new statsd\connection\mtu,
				$data = uniqid()
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu),
				$this->calling($mtu)->add = function($data, $callback) use (& $mtuAfterAdd) { $callback($mtuAfterAdd = new statsd\connection\mtu); }
			)
			->then
				->object($this->testedInstance->write($data, $callback))->isTestedInstance
				->mock($mtu)->call('add')->withIdenticalArguments($data)->once
				->object($connectionAfterWrite)
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance(new statsd\address, $mtuAfterAdd))

			->if(
				$this->newTestedInstance(new statsd\address, $mtu),
				$this->calling($mtu)->add->throw = new \exception
			)
			->then
				->exception(function() { $this->testedInstance->write(uniqid(), function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('MTU size exceeded')
		;
	}

	function testEndPacket()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterEndPacket) { $connectionAfterEndPacket = $connection; },
				$address = new statsd\address,
				$mtu = new statsd\connection\mtu
			)
			->if(
				$this->calling($address)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket); },
				$openedSocket = new statsd\socket,

				$this->calling($mtu)->writeOn = function($socket, $callback) use (& $mtuAfterWriteOn) { $callback($mtuAfterWriteOn); },
				$mtuAfterWriteOn = new statsd\connection\mtu,

				$this->newTestedInstance($address, $mtu)
			)
			->then
				->object($this->testedInstance->endPacket($callback))->isTestedInstance
				->mock($mtu)->call('writeOn')->withIdenticalArguments($openedSocket)->once
				->object($connectionAfterEndPacket)
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($address, $mtuAfterWriteOn))
		;
	}

	function testClose()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterClose) { $connectionAfterClose = $connection; },
				$address = new statsd\address,
				$this->calling($address)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket); },
				$openedSocket = new statsd\socket,
				$this->newTestedInstance($address, new statsd\connection\mtu)
			)
			->if(
				$this->calling($openedSocket)->close = function($callback) { $callback(new statsd\socket); }
			)
			->then
				->object($this->testedInstance->close($callback))->isTestedInstance
				->object($connectionAfterClose)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($openedSocket)->call('close')->once
		;
	}
}
