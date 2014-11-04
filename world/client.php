<?php

namespace estvoyage\statsd\world;

interface client
{
	function send(connection\data $data);
}
