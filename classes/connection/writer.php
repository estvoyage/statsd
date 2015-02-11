<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\net\mtu,
	estvoyage\net\socket
;

interface writer
{
	function socketHasMtu(socket\client\socket $socket, mtu $mtu);
}
