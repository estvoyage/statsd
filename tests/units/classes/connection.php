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

	function testSend()
	{
		$this
			->given(
				$packet = new packet,
				$socket = new socket,
				$mtu = new net\mtu
			)
			->if(
				$this->newTestedInstance($socket, $mtu)
			)
			->then
				->object($this->testedInstance->send($packet))->isTestedInstance
				->mock($packet)->call('writeOn')->withArguments($socket, $mtu)->once
		;
	}
}
