<?php

namespace estvoyage\statsd\tests\units\probe;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	estvoyage\statsd\bucket,
	estvoyage\statsd\metric,
	estvoyage\statsd\packet,
	mock\estvoyage\statsd\world as statsd
;

class counter extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
		;
	}

	function testIncrement()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = uniqid(),
				$count = rand(1, PHP_INT_MAX - 2),
				$start = rand(1, PHP_INT_MAX - 1)
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->increment($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, 1))->once

			->if(
				$this->newTestedInstance($client, 1)
			)
			->then
				->object($this->testedInstance->increment($bucket, $count))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, 1 + $count))->once

				->object($this->testedInstance->increment($bucket, - $count))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, 1 - $count))->once

			->if(
				$this->newTestedInstance($client, $start)
			)
			->then
				->object($this->testedInstance->increment($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, $start + 1))->once

			->if(
				$this->newTestedInstance($client, - $start)
			)
			->then
				->object($this->testedInstance->increment($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, $start + 1))->once
		;
	}

	function testDecrement()
	{
		$this
			->given(
				$client = new statsd\client,
				$bucket = uniqid(),
				$count = rand(1, PHP_INT_MAX - 2),
				$start = rand(1, PHP_INT_MAX - 1)
			)
			->if(
				$this->newTestedInstance($client)
			)
			->then
				->object($this->testedInstance->decrement($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, - 1))->once

			->if(
				$this->newTestedInstance($client, 1)
			)
			->then
				->object($this->testedInstance->decrement($bucket, $count))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, 1 - $count))->once

				->object($this->testedInstance->decrement($bucket, - $count))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, 1 + $count))->once

			->if(
				$this->newTestedInstance($client, $start)
			)
			->then
				->object($this->testedInstance->decrement($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, $start - 1))->once

			->if(
				$this->newTestedInstance($client, - $start)
			)
			->then
				->object($this->testedInstance->decrement($bucket))->isTestedInstance
				->mock($client)->call('newMetric')->withArguments(new metric\counting($bucket, $start - 1))->once
		;
	}
}
