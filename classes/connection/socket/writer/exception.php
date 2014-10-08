<?php

namespace estvoyage\statsd\connection\socket\writer;

use
	estvoyage\statsd\world as statsd
;

class exception extends \runtimeException implements statsd\connection\socket\writer\exception
{
}
