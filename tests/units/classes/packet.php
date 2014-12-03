<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd,
	estvoyage\net\socket
;

class packet extends units\test
{
	function testConstructor()
	{
		$this
			->given(
				$metric1 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX))),
				$metric2 = new statsd\metric(new statsd\bucket(uniqid()), new statsd\value\counting(rand(0, PHP_INT_MAX)))
			)
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
}
