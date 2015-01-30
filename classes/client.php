<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

final class client implements statsd\client
{
	private
		$connection
	;

	function __construct(statsd\connection $connection)
	{
		$this->connection = $connection;

		$this->init();
	}

	function __destruct()
	{
		$this->writePacketOnConnection();
	}

	function valueGoesInto(metric\value $value, metric\bucket $bucket)
	{
		$this->metrics[] = new metric($bucket, $value);

		return $this;
	}

	function noMoreValue()
	{
		return $this
			->writePacketOnConnection()
				->init()
		;
	}

	private function init()
	{
		$this->metrics = [];

		return $this;
	}

	private function writePacketOnConnection()
	{
		$this->connection
			->newPacket(
				new packet(... $this->metrics)
			)
		;

		return $this;
	}
}
