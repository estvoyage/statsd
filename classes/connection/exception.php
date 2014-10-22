<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\statsd,
	estvoyage\statsd\world\connection
;

class exception extends statsd\exception implements connection\exception
{
}
