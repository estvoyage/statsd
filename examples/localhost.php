<?php

/*
This script send three different type of metric to a local statsd server which listen on its default port, aka 8125.
1. Go to the root directory and install composer with `curl -sS https://getcomposer.org/installer | php`
2. Execute `php composer.phar install`
3. Install statsd on your localhost or execute `nc -u -l 8125` in a terminal
4. Go in the `examples` directory and execute `php localhost.php` to send metrics to your local statsd server or to `nc`.
*/

require __DIR__ . '/../vendor/autoload.php';

use
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
	->newStatsdMetric(new metric\counting(new bucket(uniqid()), new value(1)))
//	->newStatsdMetric(value::counting(-1), new bucket(uniqid()))
//	->newStatsdMetric(value::counting(42), new bucket(uniqid()))
//	->newStatsdMetric(value::counting(666, rand(1, 100) / 1000), new bucket(uniqid()))
//	->newStatsdMetric(value::counting(- 999, rand(1, 100) / 1000), new bucket(uniqid()))
;
