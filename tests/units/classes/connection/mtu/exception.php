<?php

namespace estvoyage\statsd\tests\units\connection\mtu;

require __DIR__ . '/../../../runner.php';

class exception extends \atoum
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\statsd\world\connection\mtu\exception')
			->implements('estvoyage\statsd\world\exception')
			->extends('estvoyage\statsd\exception')
		;
	}
}
