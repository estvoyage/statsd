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

	function testReset()
	{
		$this
			->given(
				$callback = function($mtu) use (& $mtuAfterReset) { $mtuAfterReset = $mtu; }
			)
			->if(
				$this->newTestedInstance(rand(1, PHP_INT_MAX))
			)
			->then
				->object($this->testedInstance->reset($callback))->isTestedInstance
				->object($mtuAfterReset)
					->isNotTestedInstance

			->if(
				$this->newTestedInstance(rand(1, PHP_INT_MAX))->add('a', function($mtu) use (& $mtuAfterAdd) { $mtuAfterAdd = $mtu; })
			)
			->then
				->object($mtuAfterAdd->reset(function($mtu) use (& $socket, & $mtuAfterReset) { $mtu = $mtuAfterReset; $mtu->writeOn($socket = new statsd\socket, function() {}); }))->isIdenticalTo($mtuAfterAdd)
				->object($mtuAfterReset)
					->isNotTestedInstance
				->mock($socket)->call('write')->withIdenticalArguments('')->once
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


		;
	}
}
