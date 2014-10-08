<?php

namespace seshat\statsd\world\connection\socket;

interface writer
{
	function writeOnResource($data, $resource);
}
