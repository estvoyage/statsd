<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\net\world\socket
;

class packet extends test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/mtu.php';
		require_once 'mock/net/socket/data.php';
		require_once 'mock/statsd/metric.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\world\packet')
		;
	}

	function testSocketHasMtu()
	{
		$this
			->given(
				$socket = new socket,
				$mtu = new net\mtu(5),
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
				->mock($socket)->call('bufferContains')->withArguments(new statsd\packet\buffer($socket, new net\socket\data('12')), new net\socket\data('12'))->once

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->socketHasMtu($socket, $mtu))->isTestedInstance
				->mock($socket)->call('bufferContains')->withArguments(new statsd\packet\buffer($socket, new net\socket\data('12' . "\n" . '45')), new net\socket\data('12' . "\n" . '45'))->once

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
				->mock($socket)
					->call('bufferContains')
						->withArguments(new statsd\packet\buffer($socket, new net\socket\data('12' . "\n" . '45')), new net\socket\data('12' . "\n" . '45'))->twice
						->withArguments(new statsd\packet\buffer($socket, new net\socket\data('78')), new net\socket\data('78'))->once
		;
	}
}
