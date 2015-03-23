<?php

use
	estvoyage\data
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
