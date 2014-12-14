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

class jumbo extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\statsd\connection')
		;
	}

	function testSend()
	{
		$this
			->given(
				$packet = new packet,
				$address = new net\address,
				$socket = new socket
			)
			->if(
				$this->newTestedInstance($address, $socket)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($packet)->call('writeOn')->withArguments($socket, $address, net\mtu::build(8932))->once
		;
	}
}
