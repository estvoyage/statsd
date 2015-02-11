<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd,
	estvoyage\net,
	mock\estvoyage\net\socket
;

class packet extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/mtu.php';
		require_once 'mock/net/socket/data.php';
		require_once 'mock/statsd/metric.php';

		$this->mockGenerator->allIsInterface();
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\connection\writer')
		;
	}

	function testSocketHasMtu()
	{
		$this
			->given(
				$socket = new socket\client\socket,
				$this->calling($socket)->buildWriteBuffer = $writeBuffer = new socket\client\socket\writeBuffer,
				$mtu = new net\mtu(6),
				$metric1 = new statsd\metric('12'),
				$metric2 = new statsd\metric('45'),
				$metric3 = new statsd\metric('78'),
				$metricGreaterThanMtu = new statsd\metric('123456')
			)

			->if(
				$this->newTestedInstance($metric1)
			)
			->then
				->object($this->testedInstance->socketHasMtu($socket, $mtu))->isTestedInstance
				->mock($writeBuffer)->receive('newData')->withArguments(new net\socket\data('12' . "\n"))->once

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->socketHasMtu($socket, $mtu))->isTestedInstance
				->mock($writeBuffer)->call('newData')->withArguments(new net\socket\data('12' . "\n" . '45' . "\n"))->once

			->if(
				$this->newTestedInstance($metricGreaterThanMtu)
			)
			->then
				->exception(function() use ($socket, $mtu) { $this->testedInstance->socketHasMtu($socket, $mtu); })
					->isInstanceOf('estvoyage\net\mtu\overflow')
					->hasMessage('Unable to split packet according to MTU')

			->if(
				$this->newTestedInstance($metric1, $metric2, $metric3)
			)
			->then
				->object($this->testedInstance->socketHasMtu($socket, $mtu))->isTestedInstance
				->mock($writeBuffer)
					->call('newData')
						->withArguments(new net\socket\data('12' . "\n" . '45' . "\n"))->twice
						->withArguments(new net\socket\data('78' . "\n"))->once
		;
	}
}
