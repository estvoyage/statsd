<?php

namespace estvoyage\statsd\connection\socket;

use
	estvoyage\statsd\world as statsd
;

class exception extends \runtimeException implements statsd\connection\socket\exception
{
}
