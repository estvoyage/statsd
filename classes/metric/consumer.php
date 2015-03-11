<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\net,
	estvoyage\data
;

final class consumer implements data\consumer
{
	private
		$dataConsumer,
		$mtu,
		$buffer
	;

	function __construct(data\consumer $dataConsumer, net\mtu $mtu)
	{
		$this->dataConsumer = $dataConsumer;
		$this->mtu = $mtu;

		$this->bufferIsEmpty();
	}

	function dataProviderIs(data\provider $dataProvider)
	{
		$dataProvider->dataConsumerIs($this);

		return $this;
	}

	function newData(data\data $data)
	{
		$dataLength = strlen($data);

		switch (true)
		{
			case $dataLength > $this->mtu->asInteger:
				throw new net\mtu\exception\overflow('Length of data \'' . $data . '\' exceed MTU ' . $this->mtu);

			case $dataLength + strlen($this->buffer) > $this->mtu->asInteger:
				$this->noMoreData();
		}

		$this->buffer = $this->buffer->newData($data);

		return $this;
	}

	function noMoreData()
	{
		$this->dataConsumer->newData($this->buffer);

		return $this->bufferIsEmpty();
	}

	private function bufferIsEmpty()
	{
		$this->buffer = new data\data;

		return $this;
	}
}
