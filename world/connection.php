<?php

namespace estvoyage\statsd\world;

interface connection
{
	function send(packet $packet);
}
