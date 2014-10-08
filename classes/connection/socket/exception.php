<?php

namespace seshat\statsd\connection\socket;

use
	seshat\statsd\world as statsd
;

class exception extends \runtimeException implements statsd\connection\socket\exception
{
}
