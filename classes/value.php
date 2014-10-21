<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\value,
	estvoyage\statsd\world as statsd
;

class value implements statsd\value
{
	private
		$value,
		$type
	;

	function __construct($value, $type)
	{
		$this->value = $value;
		$this->type = $type;
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$connection
			->write($this->value . '|' . $this->type, function($connection) use ($callback) {
					$connection->endPacket($callback);
				}
			)
		;

		return $this;
	}
}
