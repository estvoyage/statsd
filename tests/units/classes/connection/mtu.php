<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class mtu extends units\test
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
				$mtu = 2
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->add('a'))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->exception(function() { $this->testedInstance->add('aaa'); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'aaa\' exceed MTU size')
		;
	}

	function testAddIfNotEmpty()
	{
		$this
			->given(
				$mtu = 2
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->addIfNotEmpty('a'))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->exception(function() { $this->testedInstance->add('aaa'); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('\'aaa\' exceed MTU size')
		;
	}

	function testReset()
	{
		$this
			->given(
				$mtu = rand(1, PHP_INT_MAX),
				$socket = new statsd\socket
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->reset())
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

			->if(
				$this->testedInstance->add('a')->reset()->writeOn($socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('')->once
		;
	}

	function testResetIfTrue()
	{
		$this
			->given(
				$mtu = rand(1, PHP_INT_MAX),
				$socket = new statsd\socket
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->resetIfTrue(true))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

				->object($this->testedInstance->resetIfTrue(false))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)

			->if(
				$this->testedInstance->add('a')->resetIfTrue(true)->writeOn($socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('')->once

			->if(
				$this->testedInstance->add('a')->resetIfTrue(false)->writeOn($socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('a')->once
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$mtu = 2,
				$socket = new statsd\socket
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->writeOn($socket))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($socket)->call('write')->withIdenticalArguments('')->once

			->if(
				$this->testedInstance->add('a')->writeOn($socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

			->if(
				$this->calling($socket)->write->throw = new \exception()
			)
			->then
				->exception(function() use ($socket) { $this->testedInstance->writeOn($socket); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('Unable to write on socket')
		;
	}

	function testWriteIfTrueOn()
	{
		$this
			->given(
				$mtu = rand(1, PHP_INT_MAX),
				$socket = new statsd\socket
			)
			->if(
				$this->newTestedInstance($mtu)
			)
			->then
				->object($this->testedInstance->writeIfTrueOn(true, $socket))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($socket)->call('write')->withIdenticalArguments('')->once

				->object($this->testedInstance->writeIfTrueOn(false, $socket))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($socket)->call('write')->withIdenticalArguments('')->once

			->if(
				$this->testedInstance->add('a')->writeIfTrueOn(true, $socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

			->if(
				$this->testedInstance->add('a')->writeIfTrueOn(false, $socket)
			)
			->then
				->mock($socket)->call('write')->withIdenticalArguments('a')->once

			->if(
				$this->calling($socket)->write->throw = new \exception()
			)
			->then
				->exception(function() use ($socket) { $this->testedInstance->writeIfTrueOn(true, $socket); })
					->isInstanceOf('estvoyage\statsd\connection\mtu\exception')
					->hasMessage('Unable to write on socket')
		;
	}
}
