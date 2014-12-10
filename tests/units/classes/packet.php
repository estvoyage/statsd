<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\net,
	estvoyage\statsd,
	estvoyage\net\socket
;

class packet extends test
{
	function testConstructor()
	{
		$this
			->given(
				$metric1 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))),
				$metric2 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX)))
			)

			->if(
				$packet = new statsd\packet
			)
			->then
				->object($packet->data)->isEqualTo(new socket\data)

			->if(
				$this->newTestedInstance($metric1)
			)
			->then
				->object($this->testedInstance->data)->isEqualTo(new socket\data((string) $metric1))

			->if(
				$packet = new statsd\packet($metric1, $metric2)
			)
			->then
				->object($packet->data)->isEqualTo(new socket\data($metric1 . "\n" . $metric2))
		;
	}

	function testAdd()
	{
		$this
			->given(
				$metric1 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))),
				$metric2 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))),
				$metric3 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX)))
			)
			->if(
				$packet = new statsd\packet
			)
			->then

				->object($packet->add($metric1))
					->isNotIdenticalTo($packet)
					->isInstanceOf($packet)
					->toString->isEqualTo($metric1)

				->object($packet->add($metric1)->add($metric1))
					->isNotIdenticalTo($packet)
					->isInstanceOf($packet)
					->toString->isEqualTo($metric1 . "\n" . $metric1)

				->object($packet->add($metric1)->add($metric1)->add($metric2, $metric3))
					->isNotIdenticalTo($packet)
					->isInstanceOf($packet)
					->toString->isEqualTo($metric1 . "\n" . $metric1 . "\n" . $metric2 . "\n" . $metric3)
		;
	}

	function testSplit()
	{
		$this
			->given(
				$mtu = net\mtu::build(68)
			)
			->if(
				$this->newTestedInstance(new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))))
			)
			->then
				->object($this->testedInstance->split($mtu))->isEqualTo(new statsd\packet\collection($this->testedInstance))
		;
	}
}
