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
				$this->calling($mtu)->resetIfTrue = function($boolean, $callback) use (& $mtuAfterResetIfTrue) { $callback($mtuAfterResetIfTrue); },
				$mtuAfterResetIfTrue = new statsd\connection\mtu
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->startPacket($callback))->isTestedInstance
				->mock($mtu)->call('resetIfTrue')->withIdenticalArguments(true)->once

				->object($connectionAfterStartPacket->startPacket(function() {}))->isIdenticalTo($connectionAfterStartPacket)
				->mock($mtuAfterResetIfTrue)->call('resetIfTrue')->withIdenticalArguments(false)->once
		;
	}

	function testStartMetric()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterStartMetric) { $connectionAfterStartMetric = $connection; },
				$mtu = new statsd\connection\mtu,
				$this->calling($mtu)->addIfNotEmpty = function($data, $callback) use (& $mtuAfterAddIfNotEmpty) { $callback($mtuAfterAddIfNotEmpty); }
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->startMetric($callback))->isTestedInstance
				->mock($mtu)->call('addIfNotEmpty')->withIdenticalArguments("\n")->once
				->object($connectionAfterStartMetric)
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

	function testEndMetric()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterEndMetric) { $connectionAfterEndMetric = $connection; }
			)
			->if(
				$this->newTestedInstance(new statsd\address, new statsd\connection\mtu)
			)
			->then
				->object($this->testedInstance->endMetric($callback))->isTestedInstance
				->object($connectionAfterEndMetric)->isIdenticalTo($this->testedInstance)
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

				$this->calling($mtu)->writeIfTrueOn = function($boolean, $socket, $callback) use (& $mtuAfterWriteOn) { $callback($mtuAfterWriteOn); },
				$mtuAfterWriteOn = new statsd\connection\mtu,

				$this->newTestedInstance($address, $mtu)
			)
			->then
				->object($this->testedInstance->endPacket($callback))->isTestedInstance
				->mock($mtu)->call('writeIfTrueOn')->withIdenticalArguments(false, $openedSocket)->once
				->object($connectionAfterEndPacket)
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($address, $mtuAfterWriteOn))

			->if(
				$this->calling($mtu)->resetIfTrue = function($boolean, $callback) use (& $mtuAfterResetIfTrue) { $callback($mtuAfterResetIfTrue); },
				$mtuAfterResetIfTrue = new statsd\connection\mtu,

				$this->newTestedInstance($address, $mtu)->startPacket(function($connection) use (& $connectionAfterStartPacket) { $connectionAfterStartPacket = $connection; })
			)
			->then
				->object($connectionAfterStartPacket->endPacket($callback))->isIdenticalTo($connectionAfterStartPacket)
				->mock($mtuAfterResetIfTrue)->call('writeIfTrueOn')->withIdenticalArguments(true, $openedSocket)->once
				->object($connectionAfterEndPacket)
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($address, $mtuAfterWriteOn))

			->if(
				$this->calling($mtu)->writeIfTrueOn->throw = new \exception,
				$this->newTestedInstance($address, $mtu)
			)
			->then
				->exception(function() { $this->testedInstance->endPacket(function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('Unable to end packet')
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

			->if(
				$this->calling($openedSocket)->close->throw = new \exception
			)
			->then
				->exception(function() { $this->testedInstance->close(function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage('Unable to close connection')
		;
	}

	function testWriteData()
	{
		$this
			->given(
				$data = new statsd\connection\data,
				$callback = function() {}
			)
			->if(
				$this->newTestedInstance(new statsd\address, new statsd\connection\mtu)
			)
			->then
				->object($this->testedInstance->writeData($data, $callback))->isTestedInstance
				->mock($data)->call('writeOn')->withIdenticalArguments($this->testedInstance, $callback)->once
		;
	}
}
