<?php

namespace estvoyage\statsd\world;

interface value
{
	function applySampling($sampling, callable $callback);
	function send(bucket $bucket, connection $connection);
}
