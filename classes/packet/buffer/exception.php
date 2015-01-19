<?php

namespace estvoyage\statsd\packet\buffer;

use
	estvoyage\statsd\world as statsd
;

class exception extends \runtimeException implements statsd\exception
{
}
