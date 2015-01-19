<?php

namespace estvoyage\statsd\tests\units\packet;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\net\world as net,
	estvoyage\net\socket
;

class buffer extends units\test
{
	function beforeTestMethod($method)
	{
		require_once 'mock/net/socket/data.php';
	}

	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\net\world\socket\buffer')
		;
	}

	function testNewData()
	{
		$this
			->given(
				$data = new socket\data(uniqid()),
				$socket = new net\socket
			)
			->if(
				$this->newTestedInstance($socket)
			)
			->then
				->object($this->testedInstance->newData($data))->isTestedInstance
				->mock($socket)->call('bufferContains')->withIdenticalArguments($this->testedInstance, $data)->once
		;
	}

	function testRemainingData()
	{
		$this
			->given(
				$socket = new net\socket
			)
			->if(
				$this->newTestedInstance($socket)
			)
			->then
				->exception(function() { $this->testedInstance->remainingData(new socket\data); })
					->isInstanceOf('estvoyage\statsd\packet\buffer\exception')
					->hasMessage('Unable to send all data in a single network packet')
		;
	}
}
