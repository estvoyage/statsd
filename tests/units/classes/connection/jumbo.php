<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	estvoyage\net,
	estvoyage\statsd\tests\units,
	mock\estvoyage\net\world\socket,
	mock\estvoyage\statsd\world\packet
;

class jumbo extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/mtu.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\statsd\connection')
		;
	}

	function testNewPacket()
	{
		$this
			->given(
				$socket = new socket,
				$packet = new packet
			)
			->if(
				$this->newTestedInstance($socket)
			)
			->then
				->object($this->testedInstance->newPacket($packet))->isTestedInstance
				->mock($packet)->call('socketHasMtu')->withArguments($socket, net\mtu::build(8932))->once
		;
	}
}
