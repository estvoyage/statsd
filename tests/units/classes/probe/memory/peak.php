<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd\world as statsd
;

class peak extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
		;
	}

	function testMark()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = uniqid(),
				$this->function->memory_get_peak_usage[1] = $start = rand(2000, 3000),
				$this->function->memory_get_peak_usage[2] = $stop = rand(4000, 5000)
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->mark($bucket))->isTestedInstance
				->mock($client)->call('codeHasGeneratedMetrics')->withArguments(new metric\gauge($bucket, $stop - $start))->once
		;
	}
}
