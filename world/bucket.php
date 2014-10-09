<?php

namespace estvoyage\statsd\world;

interface bucket
{
	function send($value, $type, $sampleRate, connection $connection, $timeout = null);
	function sendWithSampling($value, $type, value\sampling $sampling, connection $connection, $timeout = null);
}
