<?php

namespace estvoyage\statsd\connection;

use
	estvoyage\statsd\world as statsd
;

class overflow extends \overflowException implements statsd\exception
{
}
