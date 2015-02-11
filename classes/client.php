<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

final class client implements metric\value\collector
{
	private
		$packetCollector
	;

	function __construct(packet\collector $packetCollector)
	{
		$this->packetCollector = $packetCollector;

		$this->init();
	}

	function __destruct()
	{
		$this->buildPacketForCollector();
	}

	function valueGoesInto(metric\value $value, metric\bucket $bucket)
	{
		$this->metrics[] = new metric($bucket, $value);

		return $this;
	}

	function noMoreValue()
	{
		return $this
			->buildPacketForCollector()
				->init()
		;
	}

	private function init()
	{
		$this->metrics = [];

		return $this;
	}

	private function buildPacketForCollector()
	{
		$this->packetCollector
			->newPacket(
				new packet(... $this->metrics)
			)
		;

		return $this;
	}
}
