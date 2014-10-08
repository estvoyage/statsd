<?php

namespace seshat\statsd\world;

interface connection
{
	function send($bucket, $value, $type, $sampleRate, $timeout);
}
