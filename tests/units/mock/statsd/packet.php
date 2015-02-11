<?php

namespace estvoyage\statsd;

use
	estvoyage\net\mtu,
	estvoyage\net\socket
;

class packet implements connection\writer
{
	function socketHasMtu(socket\client\socket $socket, mtu $mtu)
	{
	}
}
