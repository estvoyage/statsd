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
require_once 'mock/net/address.php';

class connection extends test
{
	function testSendMetric()
	{
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
