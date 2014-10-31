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

	function reset()
	{
		$mtu = clone $this;
		$mtu->buffer = '';

		return $mtu;
	}

	function resetIfTrue($boolean)
	{
		$mtu = clone $this;

		if ($boolean)
		{
			$mtu->buffer = '';
		}

		return $mtu;
	}

	function add($data)
	{
		if (strlen($this->buffer . $data) > $this->size)
		{
			throw new mtu\exception('\'' . $this->buffer . $data . '\' exceed MTU size');
		}

		$mtu = clone $this;
		$mtu->buffer .= $data;

		return $mtu;
	}

	function addIfNotEmpty($data)
	{
		return $this->add($this->buffer == '' ? '' : $data);
	}

	function writeOn(statsd\socket $socket)
	{
		try
		{
			$socket->write($this->buffer);
		}
		catch (\exception $exception)
		{
			throw new mtu\exception('Unable to write on socket');
		}

		$mtu = clone $this;
		$mtu->buffer = '';

		return $mtu;
	}

	function writeIfTrueOn($boolean, statsd\socket $socket)
	{
		$mtu = clone $this;

		if ($boolean)
		{
			try
			{
				$socket->write($mtu->buffer);
			}
			catch (\exception $exception)
			{
				throw new mtu\exception('Unable to write on socket');
			}

			$mtu->buffer = '';
		}

		return $mtu;
	}
}
