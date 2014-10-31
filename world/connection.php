<?php

namespace estvoyage\statsd\world;

interface connection
{
	function open(address $address);
	function startPacket();
	function startMetric();
	function write($data);
	function endMetric();
	function endPacket();
	function close();

	function writeData(connection\data $data);
}
