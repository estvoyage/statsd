<?php

namespace estvoyage\statsd\world;

use
	estvoyage\net\mtu,
	estvoyage\net\address,
	estvoyage\net\world\socket
;

interface packet
{
	function shouldBeSendOn(socket $socket, mtu $mtu);
}
