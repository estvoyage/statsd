<?php

namespace estvoyage\statsd\world;

interface connection
{
	function open(address $address, callable $callback);
	function startMetric(callable $callback);
	function write($data, callable $callback);
	function writeMetric(metric $metric, callable $callback);
	function writeMetricComponent(metric\component $component, callable $callback);
	function endMetric(callable $callback);
	function close(callable $callback);
}
