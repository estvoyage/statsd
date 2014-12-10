<?php

namespace estvoyage\statsd\tests\units\packet;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\packet,
	estvoyage\statsd\metric,
	estvoyage\statsd\bucket,
	estvoyage\statsd\value\counting
;

class collection extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('iteratorAggregate')
			->implements('estvoyage\statsd\world\packet\collection')
		;
	}

	function testAdd()
	{
		$this
			->given(
				$packet1 = new packet(new metric(new bucket(uniqid()), new counting(rand(0, PHP_INT_MAX)))),
				$packet2 = new packet(new metric(new bucket(uniqid()), new counting(rand(0, PHP_INT_MAX))))
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->add($packet1))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($packet1))

				->object($this->testedInstance->add($packet1, $packet2))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($packet1, $packet1, $packet2))
		;
	}
}
