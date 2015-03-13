<?php

namespace estvoyage\statsd\metric\consumer\exception;

use
	estvoyage\statsd\metric\consumer
;

class overflow extends \overflowException implements consumer\exception
{
}
