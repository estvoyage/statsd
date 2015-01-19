<?php

namespace estvoyage\statsd\packet;

use
	estvoyage\net\world as net,
	estvoyage\net\socket
;

class buffer implements net\socket\buffer
{
	private
		$socket
	;

	function __construct(net\socket $socket)
	{
		$this->socket = $socket;
	}

	function newData(socket\data $data)
	{
		$this->socket->bufferContains($this, $data);

		return $this;
	}

	function remainingData(socket\data $data)
	{
		throw new buffer\exception('Unable to send all data in a single network packet');
	}
}
