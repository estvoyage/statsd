<?php

namespace estvoyage\statsd\world;

interface connection
{
	function newPacket(packet $packet);
}
