<?php

namespace seshat\statsd\tests\units\connection\socket;

require __DIR__ . '/../../../runner.php';

class writer extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('seshat\statsd\world\connection\socket\writer')
		;
	}

	function testWriteOnResource()
	{
		$this
			->given(
				$resource = uniqid(),
				$data = uniqid(),
				$this->newTestedInstance
			)
			->if(
				$this->function->fwrite = function($resource, $data) { return strlen($data); }
			)
			->then
				->object($this->testedInstance->writeOnResource($data, $resource))->isTestedInstance
				->function('fwrite')->wasCalledWithArguments($resource, $data)->once


			->if(
				$this->function->fwrite[2] = 2
			)
			->then
				->object($this->testedInstance->writeOnResource($data, $resource))->isTestedInstance
				->function('fwrite')
					->wasCalledWithArguments($resource, $data)->twice
					->wasCalledWithArguments($resource, substr($data, 2))->once

			->if(
				$this->function->fwrite = false
			)
			->then
				->exception(function() use ($data, $resource) { $this->testedInstance->writeOnResource($data, $resource); })
					->isInstanceOf('seshat\statsd\connection\socket\writer\exception')
					->hasMessage('Unable to write \'' . $data . '\'')
		;
	}
}
