<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd\world as statsd
;

class metric extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\data')
		;
	}

	function testWriteOn()
	{
		$this
			->given(
				$bucket = new statsd\bucket,
				$value = new statsd\value,

				$this->calling($connection = new statsd\connection)->writeData = $connectionAfterBucketWrited = new statsd\connection,
				$this->calling($connectionAfterBucketWrited)->writeData = $connectionAfterValueWrited = new statsd\connection
			)
			->if(
				$this->newTestedInstance($bucket, $value)
			)
			->then
				->object($this->testedInstance->writeOn($connection))->isIdenticalTo($connectionAfterValueWrited)
				->mock($connection)->call('writeData')->withIdenticalArguments($bucket)->once
				->mock($connectionAfterBucketWrited)->call('writeData')->withIdenticalArguments($value)->once
		;
	}
}
