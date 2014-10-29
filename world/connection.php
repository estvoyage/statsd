<?php

namespace estvoyage\statsd\world;

interface connection
{
	function open(address $address, callable $callback);
	function startPacket(callable $callback);
	function startMetric(callable $callback);
	function write($data, callable $callback);
	function endMetric(callable $callback);
	function endPacket(callable $callback);
	function close(callable $callback);

	function writeData(connection\data $data, callable $callback);
}
