<?php

namespace estvoyage\statsd;

use
	estvoyage\net\mtu,
	estvoyage\net\socket
;

class packet
{
	use \estvoyage\value\world\immutable;

	function __construct(metric ...$metrics)
	{
		$this->initData(! $metrics ? '' : join("\n", $metrics));
	}

	function __toString()
	{
		return (string) $this->data;
	}

	function add(metric $metric, metric ...$metrics)
	{
		array_unshift($metrics, $metric);

		$data = (string) $this;

		if ($data)
		{
			$data .= "\n";
		}

		$data .= join("\n", $metrics);

		$packet = new self;
		$packet->initData($data);

		return $packet;
	}

	function split(mtu $mtu)
	{
		return new packet\collection($this);
	}

	private function initData($data)
	{
		$this->init(['data' => new socket\data($data) ]);

		return $this;
	}
}
