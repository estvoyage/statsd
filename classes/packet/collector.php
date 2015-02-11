<?php

namespace estvoyage\statsd\packet;

use
	estvoyage\statsd
;

interface collector
{
	function newPacket(statsd\packet $packet);
}
