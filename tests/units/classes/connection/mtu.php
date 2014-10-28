<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	mock\estvoyage\statsd\world as statsd
;

class mtu extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\mtu')
		;
	}

	function test__construct()
	{
		$this
			->exception(function() use (& $mtu) { $this->newTestedInstance($mtu = rand(- PHP_INT_MAX, 0)); })
				->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
				->hasMessage('\'' . $mtu . '\' is not a valid MTU')

			->exception(function() use (& $mtu) { $this->newTestedInstance($mtu = 0.1); })
				->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
				->hasMessage('\'' . $mtu . '\' is not a valid MTU')
		;
	}

	function testAdd()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterAdd) { $mtuAfterAdd = $mtu; }
			)
			->if(
				$this->newTestedInstance(2)
			)
			->then
				->object($this->testedInstance->add('a', $callback))->isTestedInstance
				->object($mtuAfterAdd)
					->isNotTestedInstance

				->exception(function() { $this->testedInstance->add('aaa', function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'aaa\' exceed MTU size')
		;
	}

	function testAddIfNotEmpty()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterAddIfNotEmpty) { $mtuAfterAddIfNotEmpty = $mtu; }
			)
			->if(
				$this->newTestedInstance(2)
			)
			->then
				->object($this->testedInstance->addIfNotEmpty('a', $callback))->isTestedInstance
				->object($mtuAfterAddIfNotEmpty)
					->isNotTestedInstance

				->exception(function() use ($mtuAfterAddIfNotEmpty) { $mtuAfterAddIfNotEmpty->add('aaa', function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'aaa\' exceed MTU size')
		;
	}

	function testReset()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterReset) { $mtuAfterReset = $mtu; }
			)
			->if(
				$this->newTestedInstance(rand(1, PHP_INT_MAX))->add('a', function($mtu) use (& $mtuAfterAdd) { $mtuAfterAdd = $mtu; })
			)
			->then
				->object($this->testedInstance->reset(function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once

				->object($mtuAfterAdd->reset(function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isIdenticalTo($mtuAfterAdd)
				->mock($socket)->call('write')->withIdenticalArguments('')->once
		;
	}

	function testResetIfTrue()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterResetIfTrue) { $mtuAfterResetIfTrue = $mtu; }
			)
			->if(
				$this->newTestedInstance(rand(1, PHP_INT_MAX))->add('a', function($mtu) use (& $mtuAfterAdd) { $mtuAfterAdd = $mtu; })
			)
			->then
				->object($this->testedInstance->resetIfTrue(true, function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once

				->object($this->testedInstance->resetIfTrue(false, function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once

				->object($mtuAfterAdd->resetIfTrue(true, function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isIdenticalTo($mtuAfterAdd)
				->mock($socket)->call('write')->withIdenticalArguments('')->once

				->object($mtuAfterAdd->resetIfTrue(false, function($mtu) use (& $socket) { $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isIdenticalTo($mtuAfterAdd)
				->mock($socket)->call('write')->withIdenticalArguments('a')->once
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$socket = new statsd\socket,
				$callback = function($mtu) use (& $mtuAfterWriteOn) { $mtuAfterWriteOn = $mtu; }
			)
			->if(
				$this->newTestedInstance(2)
			)
			->then
				->object($this->testedInstance->writeOn($socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once
				->object($mtuAfterWriteOn)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

			->if(
				$this->testedInstance->add('a', function($mtu) use (& $mtuWrited) { $mtuWrited = $mtu; })
			)
			->then
				->object($this->testedInstance->writeOn($socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->twice

				->object($mtuWrited->writeOn($socket, $callback))->isIdenticalTo($mtuWrited)
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

				->object($mtuAfterWriteOn->writeOn($socket, function() {}))->isIdenticalTo($mtuAfterWriteOn)
				->mock($socket)->call('write')->withIdenticalArguments('')->thrice

			->if(
				$this->calling($socket)->write->throw = new \exception()
			)
			->then
				->exception(function() use ($socket) { $this->testedInstance->writeOn($socket, function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('Unable to write on socket')
		;
	}

	function testWriteIfTrueOn()
	{
		$this
			->given(
				$socket = new statsd\socket,
				$callback = function($mtu) use (& $mtuAfterWriteOn) { $mtuAfterWriteOn = $mtu; }
			)
			->if(
				$this->newTestedInstance(2)
			)
			->then
				->object($this->testedInstance->writeIfTrueOn(true, $socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once
				->object($mtuAfterWriteOn)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->object($this->testedInstance->writeIfTrueOn(false, $socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once
				->object($mtuAfterWriteOn)
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

			->if(
				$this->testedInstance->add('a', function($mtu) use (& $mtuWrited) { $mtuWrited = $mtu; })
			)
			->then
				->object($this->testedInstance->writeIfTrueOn(true, $socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->twice

				->object($mtuWrited->writeIfTrueOn(true, $socket, $callback))->isIdenticalTo($mtuWrited)
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

				->object($mtuAfterWriteOn->writeIfTrueOn(true, $socket, function() {}))->isIdenticalTo($mtuAfterWriteOn)
				->mock($socket)->call('write')->withIdenticalArguments('')->thrice

				->object($this->testedInstance->writeIfTrueOn(false, $socket, $callback))->isTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->thrice

				->object($mtuWrited->writeIfTrueOn(false, $socket, $callback))->isIdenticalTo($mtuWrited)
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

				->object($mtuAfterWriteOn->writeIfTrueOn(false, $socket, function() {}))->isIdenticalTo($mtuAfterWriteOn)
				->mock($socket)->call('write')->withIdenticalArguments('')->thrice

			->if(
				$this->calling($socket)->write->throw = new \exception()
			)
			->then
				->exception(function() use ($socket) { $this->testedInstance->writeIfTrueOn(true, $socket, function() {}); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('Unable to write on socket')

				->object($this->testedInstance->writeIfTrueOn(false, $socket, function() {}))->isTestedInstance
		;
	}
}
