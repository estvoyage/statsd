<?php

namespace estvoyage\statsd\world\connection\socket;

interface writer
{
	function writeOnResource($data, $resource);
}
