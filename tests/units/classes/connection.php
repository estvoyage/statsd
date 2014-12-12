<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\net\world\socket,
	mock\estvoyage\statsd\world\packet
;

class connection extends test
{
	function testSendMetric()
	{
		require __DIR__ . '/../mock/net/mtu.php';
		require __DIR__ . '/../mock/net/address.php';

		$this
			->given(
				$packet = new packet,
				$mtu = new net\mtu,
				$socket = new socket,
				$address = new net\address
			)
			->if(
				$this->newTestedInstance($address, $socket, $mtu)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($packet)->call('writeOn')->withArguments($socket, $address, $mtu)->once
		;
	}
}
