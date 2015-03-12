<?php

/*
This script send metrics to a console or a any local udp socket on port 8125.
To use it:
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`;
2. Execute `php composer.phar install`;
3. Install statsd on your localhost or execute `nc -u -l 8125` (or `nc -u -l -p 8125` on OSX) in a terminal;
4. Go in the `examples` directory and execute `php localhost.php` to send metrics.
*/

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\metric\bucket,
	estvoyage\statsd\metric\value
;

class console implements data\consumer
{
	function dataProviderIs(data\provider $dataProvider)
	{
	}

	function newData(data\data $data)
	{
		echo $data . PHP_EOL;

		return $this;
	}

	function noMoreData()
	{
		return $this;
	}
}

(new statsd\client\etsy(new console))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;

(new statsd\client\etsy(new net\socket\client\native\udp(new net\host('127.0.0.1'), new net\port(8125))))
	->newStatsdMetric(new metric\counting(new bucket(uniqid())))
;
