<?php

namespace estvoyage\statsd\world;

interface value
{
	function send(bucket $bucket, connection $connection);
}
