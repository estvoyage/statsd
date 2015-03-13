<?php

namespace estvoyage\statsd\client;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class etsy extends generic implements data\provider
{
	function __construct(data\consumer $dataConsumer)
	{
		parent::__construct(new metric\factory\etsy($dataConsumer));
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		return new self($dataConsumer);
	}
}
