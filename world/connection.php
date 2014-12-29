<?php

namespace estvoyage\statsd\world;

interface connection
{
	function packetShouldBeSend(packet $packet);
}
