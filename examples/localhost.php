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
	estvoyage\statsd\metric\value,
	estvoyage\statsd\metric\sampling
;

class console implements data\consumer
{
	function dataProviderIs(data\provider $dataProvider)
	{
		$dataProvider->dataConsumerIs($this);

		return $this;
	}

	function newData(data\data $data)
	{
		echo 'New metric: <' . str_replace("\n", '\n' , $data) . '>' . PHP_EOL;

		return $this;
	}

	function noMoreData()
	{
		return $this;
	}
}

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

(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
	->newStatsdMetric(new metric\counting(new bucket('gorets')))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(999), new sampling(.1)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
;

(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
	->newStatsdMetric(
		(new metric\packet)
			->newStatsdMetric(new metric\counting(new bucket('gorets')))
			->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
			->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
			->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
	)
;

(new statsd\client\etsy(new metric\consumer\dataConsumerWithMtu(new console, new net\mtu(68))))
	->newStatsdMetric(
		(new metric\packet)
			->newStatsdMetric(new metric\counting(new bucket('gorets')))
			->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
			->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
			->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
			->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
	)
;

(new metric\packet)
	->newStatsdMetric(new metric\counting(new bucket('gorets')))
	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
		->statsdClientIs(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
;

(new worker)
	->itIsNapTime()
		->itIsNapTime()
			->statsdClientIs(new statsd\client\etsy(new metric\consumer\dataConsumer(new console)))
;

//(new statsd\client\etsy(new metric\consumer\dataConsumer(new net\socket\client\native\udp(new net\host('127.0.0.1'), new net\port(8125)))))
//	->newStatsdMetric(new metric\counting(new bucket('gorets')))
//	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(666)))
//	->newStatsdMetric(new metric\counting(new bucket('gorets'), new value(999), new sampling(.1)))
//	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320)))
//	->newStatsdMetric(new metric\timing(new bucket('glork'), new value(320), new sampling(.1)))
//	->newStatsdMetric(new metric\gauge(new bucket('gaugor'), new value(333)))
//	->newStatsdMetric(new metric\set(new bucket('uniques'), new value(765)))
//;

/* Output should be something like:
New metric: <gorets:1|c\n>
New metric: <gorets:666|c\n>
New metric: <gorets:999|c|@0.1\n>
New metric: <glork:320|ms\n>
New metric: <glork:320|ms|@0.1\n>
New metric: <gaugor:333|g\n>
New metric: <uniques:765|s\n>
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\nuniques:765|s\n>
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\n>
New metric: <uniques:765|s\n>
New metric: <gorets:1|c\ngorets:666|c\nglork:320|ms\nglork:320|ms|@0.1\ngaugor:333|g\nuniques:765|s\n>
*/
