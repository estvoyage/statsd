<?php

/*
This script collect metrics from a worker and send it to a console.
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Go in the `examples` directory and execute `php localhost.php` to send metrics.

WARNING: this file use sleep(), so it can take several seconds to execute!
*/

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/classes/console.php';

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

class worker implements metric\provider
{
	private
		$packet
	;

	function __construct()
	{
		$this->packet = new metric\packet;
	}

	function itIsNapTime()
	{
		$this->packet->newStatsdMetric(new metric\counting(new metric\bucket('nap')));

		$timeProbe = new statsd\probe\timer;

		sleep(rand(1, 5));

		$timeProbe
			->newStatsdBucket(new bucket('napDuration'))
				->statsdClientIs($this->packet)
		;

		return $this;
	}

	function statsdClientIs(statsd\client $statsdClient)
	{
		$statsdClient->newStatsdMetric($this->packet);

		return $this;
	}
}

(new worker)
	->itIsNapTime()
		->itIsNapTime()
			->statsdClientIs(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
;

/* Output should be something like:
New metric: <nap:1|c\nnapDuration:10000|ms\nnap:1|c\nnapDuration:50000|ms\n>
*/
