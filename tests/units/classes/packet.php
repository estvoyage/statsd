<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	mock\estvoyage\net\world\socket
;

require_once 'mock/net/mtu.php';
require_once 'mock/net/socket/data.php';
require_once 'mock/statsd/metric.php';

class packet extends test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\world\packet')
		;
	}

	function testAdd()
	{
		$this
			->given(
				$metric1 = new statsd\metric,
				$metric2 = new statsd\metric,
				$metric3 = new statsd\metric
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric1))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($metric1))

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric1)->add($metric1))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($metric1, $metric1))

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($metric1)->add($metric1)->add($metric2, $metric3))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($metric1, $metric1, $metric2, $metric3))

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->add($metric3))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($metric1, $metric2, $metric3))
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$this->calling($socket = new socket)->write = new net\socket\data,
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
				->object($this->testedInstance->writeOn($socket, $mtu))->isTestedInstance
				->mock($socket)->call('writeAll')->withArguments(new net\socket\data('12'))->once

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->writeOn($socket, $mtu))->isTestedInstance
				->mock($socket)->call('writeAll')->withArguments(new net\socket\data('12' . "\n" . '45'))->once

			->if(
				$this->newTestedInstance($metricGreaterThanMtu)
			)
			->then
				->exception(function() use ($socket, $mtu) { $this->testedInstance->writeOn($socket, $mtu); })
					->isInstanceOf('estvoyage\net\mtu\overflow')
					->hasMessage('Unable to split packet according to MTU')

			->if(
				$this->newTestedInstance($metric1, $metric2, $metric3)
			)
			->then
				->object($this->testedInstance->writeOn($socket, $mtu))->isTestedInstance
				->mock($socket)
					->call('writeAll')
						->withArguments(new net\socket\data('12' . "\n" . '45'))->twice
						->withArguments(new net\socket\data('78'))->once
		;
	}
}
