<?php

namespace estvoyage\statsd\world;

interface connection
{
	function send($value, $timeout);
}
