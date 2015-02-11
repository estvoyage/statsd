<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\statsd\packet,
	mock\estvoyage\net\socket\client\socket
;

require_once 'mock/net/mtu.php';

class connection extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/packet.php';

		$this->mockGenerator->allIsInterface();
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\packet\collector')
		;
	}

	function testNewPacket()
	{
		$this
			->given(
				$socket = new socket,
				$packet = new packet,
				$mtu = new net\mtu
			)

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
