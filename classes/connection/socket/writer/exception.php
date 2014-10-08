<?php

namespace seshat\statsd\connection\socket\writer;

use
	seshat\statsd\world as statsd
;

class exception extends \runtimeException implements statsd\connection\socket\writer\exception
{
}
