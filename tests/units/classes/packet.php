<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd,
	estvoyage\net,
	mock\estvoyage\net\world\socket
;

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
		require __DIR__ . '/../mock/statsd/metric.php';

		$this
			->given(
				$metric1 = new statsd\metric(uniqid()),
				$metric2 = new statsd\metric(uniqid()),
				$metric3 = new statsd\metric(uniqid())
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
		require __DIR__ . '/../mock/net/mtu.php';
		require __DIR__ . '/../mock/net/address.php';
		require __DIR__ . '/../mock/net/socket/data.php';
		require __DIR__ . '/../mock/statsd/metric.php';

		$this
			->given(
				$socket = new socket,
				$address = new net\address,
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
				->object($this->testedInstance->writeOn($socket, $address, $mtu))->isTestedInstance
				->mock($socket)->call('write')->withArguments(new net\socket\data('12'), $address)->once

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->writeOn($socket, $address, $mtu))->isTestedInstance
				->mock($socket)->call('write')->withArguments(new net\socket\data('12' . "\n" . '45'), $address)->once

			->if(
				$this->newTestedInstance($metricGreaterThanMtu)
			)
			->then
				->exception(function() use ($socket, $address, $mtu) { $this->testedInstance->writeOn($socket, $address, $mtu); })
					->isInstanceOf('estvoyage\net\mtu\overflow')
					->hasMessage('Unable to split packet according to MTU')

			->if(
				$this->newTestedInstance($metric1, $metric2, $metric3)
			)
			->then
				->object($this->testedInstance->writeOn($socket, $address, $mtu))->isTestedInstance
				->mock($socket)
					->call('write')
						->withArguments(new net\socket\data('12' . "\n" . '45'), $address)->twice
						->withArguments(new net\socket\data('78'), $address)->once
		;
	}
}
