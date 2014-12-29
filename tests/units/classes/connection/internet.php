<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

use
	estvoyage\net,
	estvoyage\statsd\tests\units,
	mock\estvoyage\net\world\socket,
	mock\estvoyage\statsd\world\packet
;

require_once 'mock/net/mtu.php';
require_once 'mock/net/address.php';

class internet extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\statsd\connection')
		;
	}

	function testPacketShouldBeSend()
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
				->object($this->testedInstance->packetShouldBeSend($packet))->isTestedInstance
				->mock($packet)->call('shouldBeSendOn')->withArguments($socket, net\mtu::build(512))->once
		;
	}
}
