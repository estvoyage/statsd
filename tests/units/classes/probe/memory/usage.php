<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd as mock
;

class usage extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/statsd/metric/bucket.php';
	}

	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric\builder')
		;
	}

	function testBucketIs()
	{
		$this
			->given(
				$valueCollector = new mock\metric\value\collector,
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_usage[1] = $start = rand(2000, 3000),
				$this->function->memory_get_usage[2] = $stop = rand(4000, 5000)
			)
			->if(
				$this->newTestedInstance($valueCollector)
			)
			->then
				->object($this->testedInstance->bucketIs($bucket))->isTestedInstance
				->mock($valueCollector)->call('valueGoesInto')->withArguments(metric\value::gauge($stop - $start), $bucket)->once
		;
	}
}
