<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\net\world\socket,
	mock\estvoyage\statsd\world\packet
;

require_once 'mock/net/mtu.php';

class connection extends test
{
	function testClass()
	{
		$this->testedClass
			->isAbstract
			->implements('estvoyage\statsd\world\connection')
		;
	}

	function testNewPacket()
	{
		$this
			->given(
				$packet = new packet,
				$socket = new socket,
				$mtu = new net\mtu
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->newPacket($packet))->isTestedInstance
				->mock($packet)->call('socketHasMtu')->withArguments(new net\socket\udp(new net\host('127.0.0.1'), new net\port(8125)), new net\mtu(512))->once

			->if(
				$this->newTestedInstance($socket)
			)
			->then
				->object($this->testedInstance->newPacket($packet))->isTestedInstance
				->mock($packet)->call('socketHasMtu')->withArguments($socket, new net\mtu(512))->once

			->if(
				$this->newTestedInstance($socket, $mtu)
			)
			->then
				->object($this->testedInstance->newPacket($packet))->isTestedInstance
				->mock($packet)->call('socketHasMtu')->withArguments($socket, $mtu)->once
		;
	}
}
