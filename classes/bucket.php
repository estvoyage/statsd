<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class bucket implements world\bucket
{
	private
		$value
	;

	function __construct($value)
	{
		$this->value = $value;
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$connection
			->startPacket(function($connection) use ($callback) {
					$connection->write($this->value . ':', $callback);
				}
			)
		;

		return $this;
	}
}
