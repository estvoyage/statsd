<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\statsd\world as statsd
;

class mtu implements statsd\connection\mtu
{
	private
		$buffer
	;

	function __construct($size)
	{
		if ($size < 1 || filter_var($size, FILTER_VALIDATE_INT) === false)
		{
			throw new mtu\exception('\'' . $size . '\' is not a valid MTU');
		}

		$this->buffer = '';
		$this->size = $size;
	}

	function add($data, callable $callback)
	{
		if (strlen($this->buffer . $data) > $this->size)
		{
			throw new mtu\exception('\'' . $this->buffer . $data . '\' exceed MTU size');
		}

		$mtu = clone $this;
		$mtu->buffer .= $data;

		$callback($mtu);

		return $this;
	}

	function writeOn(statsd\socket $socket, callable $callback)
	{
		$socket->write($this->buffer);

		$mtu = clone $this;
		$mtu->buffer = '';

		$callback($mtu);

		return $this;
	}
}
