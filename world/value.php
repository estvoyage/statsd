<?php

namespace seshat\statsd\world;

interface value
{
	function send(bucket $bucket, connection $connection);
}
