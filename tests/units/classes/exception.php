<?php

namespace estvoyage\statsd\tests\units;

require __DIR__ . '/../runner.php';

class exception extends test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\exception')
			->extends('exception')
		;
	}
}
