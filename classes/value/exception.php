<?php

namespace seshat\statsd\value;

use
	seshat\statsd\world\value
;

class exception extends \invalidArgumentException implements value\exception
{
}
