<?php

namespace estvoyage\statsd\world\packet;

use
	estvoyage\statsd\packet
;

interface collection
{
	function add(packet $packet, packet... $packet);
}
