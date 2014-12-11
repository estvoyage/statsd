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
	function testClass()
	{
		$this->testedClass
			->isFinal
		;
	}

	function testConstructor()
	{
		$this
			->given(
				$metric1 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))),
				$metric2 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX)))
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->data)->isEqualTo(new socket\data)

			->if(
				$this->newTestedInstance($metric1)
			)
			->then
				->object($this->testedInstance->data)->isEqualTo(new socket\data((string) $metric1))

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->data)->isEqualTo(new socket\data($metric1 . "\n" . $metric2))
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

	function testSplit()
	{
		$this
			->given(
				$mtu = net\mtu::build(68),
				$metric1 = new statsd\metric(new statsd\bucket(str_repeat('a', 26)), new statsd\value\counting(1000)),
				$metric2 = new statsd\metric(new statsd\bucket(str_repeat('b', 26)), new statsd\value\counting(2000)),
				$metric3 = new statsd\metric(new statsd\bucket(str_repeat('b', 26)), new statsd\value\counting(3000)),
				$metricGreaterThanMtu = new statsd\metric(new statsd\bucket(str_repeat('a', $mtu->asInteger + 1)), new statsd\value\counting(rand(0, PHP_INT_MAX)))
			)

			->if(
				$this->newTestedInstance($metric1)
			)
			->then
				->object($this->testedInstance->split($mtu))->isEqualTo(new statsd\packet\collection($this->testedInstance))

			->if(
				$this->newTestedInstance($metric1, $metric2)
			)
			->then
				->object($this->testedInstance->split($mtu))->isEqualTo(new statsd\packet\collection($this->testedInstance))

			->if(
				$this->newTestedInstance($metricGreaterThanMtu)
			)
			->then
				->exception(function() use ($mtu) { $this->testedInstance->split($mtu); })
					->isInstanceOf('estvoyage\net\mtu\overflow')
					->hasMessage('Unable to split packet according to MTU')

			->if(
				$this->newTestedInstance($metric1, $metric2, $metric3)
			)
			->then
				->object($this->testedInstance->split($mtu))
					->isEqualTo(
						new statsd\packet\collection(
							$this->newTestedInstance($metric1, $metric2),
							$this->newTestedInstance($metric3)
						)
					)
		;
	}
}
