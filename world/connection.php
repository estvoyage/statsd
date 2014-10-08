<?php

namespace estvoyage\statsd\world;

interface connection
{
	function send($bucket, $value, $type, $sampleRate, $timeout);
}
