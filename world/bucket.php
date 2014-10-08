<?php

namespace estvoyage\statsd\world;

interface bucket
{
	function send($value, $type, $sampleRate, connection $connection, $timeout);
}
