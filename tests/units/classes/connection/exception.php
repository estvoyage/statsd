<?php

namespace estvoyage\statsd\tests\units\connection;

require __DIR__ . '/../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('estvoyage\statsd\exception')
		;
	}
}
