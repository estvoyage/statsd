<?php

namespace estvoyage\statsd\tests\units\probe\memory;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\metric,
	mock\estvoyage\statsd\world as statsd
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
		;
	}

	function testStartMission()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = new metric\bucket(uniqid())
			)
			->if(
				$this->newTestedInstance($client, $bucket)
			)
			->then
				->object($this->testedInstance->startOfMission())->isTestedInstance

				->exception(function() {
						$this->testedInstance->startOfMission();
					}
				)
					->isInstanceOf('logicException')
					->hasMessage('Mission is already started')
		;
	}

	function testEndOfMission()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = new metric\bucket(uniqid()),
				$this->function->memory_get_usage[1] = $start = rand(2000, 3000),
				$this->function->memory_get_usage[2] = $stop = rand(4000, 5000)
			)

			->if(
				$this->newTestedInstance($client, $bucket)
			)
			->then
				->exception(function() {
						$this->testedInstance->endOfMission();
					}
				)
					->isInstanceOf('logicException')
					->hasMessage('Mission is not started')

			->if(
				$this->testedInstance
					->startOfMission()
						->endOfMission()
			)
			->then
				->mock($client)
					->call('valueGoesInto')
						->withArguments(metric\value::gauge($stop - $start), $bucket)
							->once
		;
	}
}
