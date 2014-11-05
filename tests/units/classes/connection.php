<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\socket,
	mock\estvoyage\statsd\world as statsd
;

class connection extends units\test
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
				$this->calling($address = new statsd\address)->openSocket = new statsd\socket
			)
			->if(
				$this->newTestedInstance($address, new statsd\connection\mtu)
			)
			->then
				->mock($address)->call('openSocket')->once

			->if(
				$this->calling($address)->openSocket->throw = new \exception($message = uniqid())
			)
			->then
				->exception(function() use ($address) { $this->newTestedInstance($address, new statsd\connection\mtu); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage($message)
		;
	}

	function testOpen()
	{
		$this
			->given(
				$this->calling($address = new statsd\address)->openSocket = $openedSocket = new statsd\socket,

				$this->newTestedInstance($address, new statsd\connection\mtu)
			)
			->if(
				$this->calling($otherAddress = new statsd\address)->openSocket = $otherOpenedSocket = new statsd\socket
			)
			->then
				->object($this->testedInstance->open($otherAddress))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($otherAddress)->call('openSocket')->withArguments($openedSocket)->once

			->if(
				$this->calling($otherAddress)->openSocket->throw = new \exception($message = uniqid())
			)
			->then
				->exception(function() use ($otherAddress) { $this->testedInstance->open($otherAddress); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage($message)
		;
	}

	function testStartPacket()
	{
		$this
			->given(
				$this->calling($mtu = new statsd\connection\mtu)->resetIfTrue = $mtuAfterResetIfTrue = new statsd\connection\mtu
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->startPacket())
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($mtu)->call('resetIfTrue')->withIdenticalArguments(true)->once

			->if(
				$this->testedInstance->startPacket()->startPacket()
			)
			->then
				->mock($mtuAfterResetIfTrue)->call('resetIfTrue')->withIdenticalArguments(false)->once
		;
	}

	function testStartMetric()
	{
		$this
			->if(
				$this->newTestedInstance(new statsd\address, new statsd\connection\mtu)
			)
			->then
				->object($this->testedInstance->startMetric())->isTestedInstance
		;
	}

	function testWrite()
	{
		$this
			->given(
				$this->calling($mtu = new statsd\connection\mtu)->add = $mtuAfterAdd = new statsd\connection\mtu,
				$data = uniqid()
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->write($data))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($mtu)->call('add')->withIdenticalArguments($data)->once

			->if(
				$this->testedInstance->write($data)->write($data)
			)
			->then
				->mock($mtuAfterAdd)->call('add')->withIdenticalArguments($data)->once

			->if(
				$this->calling($mtu)->add->throw = new \exception($message = uniqid())
			)
			->then
				->exception(function() { $this->testedInstance->write(uniqid()); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage($message)
		;
	}

	function testEndMetric()
	{
		$this
			->given(
				$this->calling($mtu = new statsd\connection\mtu)->addIfNotEmpty = $mtuAfterAddIfNotEmpty = new statsd\connection\mtu
			)
			->if(
				$this->newTestedInstance(new statsd\address, $mtu)
			)
			->then
				->object($this->testedInstance->endMetric())
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($mtu)->call('addIfNotEmpty')->withIdenticalArguments("\n")->once

			->if(
				$this->testedInstance->endMetric()->endMetric()
			)
			->then
				->mock($mtuAfterAddIfNotEmpty)->call('addIfNotEmpty')->withIdenticalArguments("\n")->once
		;
	}

	function testEndPacket()
	{
		$this
			->given(
				$this->calling($address = new statsd\address)->openSocket = $openedSocket = new statsd\socket,
				$this->calling($mtu = new statsd\connection\mtu)->writeIfTrueOn = $mtuAfterWriteOn = new statsd\connection\mtu
			)
			->if(
				$this->newTestedInstance($address, $mtu)
			)
			->then
				->object($this->testedInstance->endPacket())
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($mtu)->call('writeIfTrueOn')->withIdenticalArguments(false, $openedSocket)->once

			->if(
				$this->calling($mtu)->resetIfTrue = $mtuAfterResetIfTrue = new statsd\connection\mtu,
				$this->testedInstance->startPacket()->endPacket()
			)
			->then
				->mock($mtuAfterResetIfTrue)->call('writeIfTrueOn')->withIdenticalArguments(true, $openedSocket)->once

			->if(
				$this->calling($mtu)->writeIfTrueOn->throw = new \exception($message = uniqid())
			)
			->then
				->exception(function() { $this->testedInstance->endPacket(); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage($message)
		;
	}

	function testClose()
	{
		$this
			->given(
				$this->calling($address = new statsd\address)->openSocket = $openedSocket = new statsd\socket,
				$this->calling($openedSocket)->close = new statsd\socket
			)
			->if(
				$this->newTestedInstance($address, new statsd\connection\mtu)
			)
			->then
				->object($this->testedInstance->close())
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($openedSocket)->call('close')->once

			->if(
				$this->calling($openedSocket)->close->throw = new \exception($message = uniqid())
			)
			->then
				->exception(function() { $this->testedInstance->close(); })
					->isInstanceOf('estvoyage\statsd\connection\exception')
					->hasMessage($message)
		;
	}

	function testWriteData()
	{
		$this
			->given(
				$this->calling($data = new statsd\connection\data)->writeOn = $connectionAfterWriteOn = new statsd\connection
			)
			->if(
				$this->newTestedInstance(new statsd\address, new statsd\connection\mtu)
			)
			->then
				->object($this->testedInstance->writeData($data))->isIdenticalTo($connectionAfterWriteOn)
				->mock($data)->call('writeOn')->withIdenticalArguments($this->testedInstance)->once
		;
	}
}
