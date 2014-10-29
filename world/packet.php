<?php

namespace estvoyage\statsd\world;

interface packet extends connection\data
{
	function add(metric $metric, callable $callback);
}
