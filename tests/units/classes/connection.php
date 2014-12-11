<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\net\world\socket
;

class connection extends test
{
	function testSendMetric()
	{
		$this
			->given(
				$mtu = net\mtu::build(68),
				$socket = new socket,
				$address = new net\address(new net\host('foo'), new net\port(rand(0, 65535))),
				$metric = new statsd\metric(new statsd\bucket('foo'), new statsd\value\timing(0)),
				$metricGreaterThanMtu = new statsd\metric(new statsd\bucket(str_repeat('a', 69)), new statsd\value\timing(0))
			)
			->if(
				$this->newTestedInstance($address, $socket, $mtu)
			)
			->then
				->object($this->testedInstance->sendMetric($metric))->isTestedInstance
				->mock($socket)->call('write')->withArguments(new net\socket\data((string) $metric), $address)->once

				->exception(function() use ($metricGreaterThanMtu) { $this->testedInstance->sendMetric($metricGreaterThanMtu); })
					->isInstanceOf('estvoyage\net\mtu\overflow')
					->hasMessage('Metric length exceed MTU')
		;
	}
}
