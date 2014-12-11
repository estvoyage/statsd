<?php

namespace estvoyage\statsd;

use
	estvoyage\net\mtu,
	estvoyage\net\socket
;

final class packet
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

		return self::build($data . ($data ? "\n" : '') . join("\n", $metrics));
	}

	function split(mtu $mtu)
	{
		$collection = new packet\collection;

		$data = (string) $this;

		while (strlen($data) > $mtu->asInteger)
		{
			$packet = substr($data, 0, $mtu->asInteger);
			$endOfMetric = strrpos($packet, "\n");

			if (! $endOfMetric)
			{
				throw new mtu\overflow('Unable to split packet according to MTU');
			}

			$collection = self::addToCollection($collection, substr($packet, 0, $endOfMetric));

			$data = substr($packet, $endOfMetric + 1) . substr($data, $mtu->asInteger);
		}

		return self::addToCollection($collection, $data);
	}

	private function initData($data)
	{
		return $this->init(['data' => new socket\data($data) ]);
	}

	private static function build($data)
	{
		return (new self)->initData($data);
	}

	private static function addToCollection(packet\collection $collection, $data)
	{
		return ! $data ? $collection : $collection->add(self::build($data));
	}
}
