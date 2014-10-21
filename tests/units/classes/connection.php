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

	function testOpen()
	{
		$this
			->given(
				$address = new statsd\address
			)
			->if(
				$this->calling($address)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket = new statsd\socket); },
				$callback = function($connection) use (& $openedConnection) { $openedConnection = $connection; },
				$this->newTestedInstance($address)
			)
			->then
				->object($this->testedInstance->open($callback))->isTestedInstance
				->mock($address)->call('openSocket')->withArguments(new socket)->once
				->object($openedConnection)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
		;
	}

	function testStartPacket()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterStartPacket) { $connectionAfterStartPacket = $connection; }
			)
			->if(
				$this->newTestedInstance(new statsd\address)
			)
			->then
				->object($this->testedInstance->startPacket($callback))->isTestedInstance
				->object($connectionAfterStartPacket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
		;
	}

	function testEndPacket()
	{
		$this
			->given(
				$callback = function($connection) use (& $connectionAfterEndPacket) { $connectionAfterEndPacket = $connection; },
				$address = new statsd\address
			)
			->if(
				$this->calling($address)->openSocket = function($socket, $callback) use (& $openedSocket) { $callback($openedSocket); },
				$openedSocket = new statsd\socket,
				$this->newTestedInstance($address)
			)
			->then
				->object($this->testedInstance->endPacket($callback))->isTestedInstance
				->object($connectionAfterEndPacket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($openedSocket)->call('write')->withArguments('')->once

			->given(
				$component = new statsd\packet\component,
				$this->calling($component)->writeOn = function($connection, $callback) { $connection->write('foo', $callback); }
			)
			->if(
				$component->writeOn($this->testedInstance, function($connection) use (& $connectionWrited) { $connectionWrited = $connection; })
			)
			->then
				->object($this->testedInstance->endPacket($callback))->isTestedInstance
				->mock($openedSocket)->call('write')->withArguments('')->twice

				->object($connectionWrited->endPacket($callback))->isIdenticalTo($connectionWrited)
				->object($connectionAfterEndPacket)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($openedSocket)->call('write')->withArguments('foo')->once

				->object($connectionAfterEndPacket->endPacket(function() {}))->isIdenticalTo($connectionAfterEndPacket)
				->mock($openedSocket)->call('write')->withArguments('')->thrice
		;
	}
}
