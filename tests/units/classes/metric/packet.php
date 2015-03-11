<?php

namespace estvoyage\statsd\tests\units\metric;

require __DIR__ . '/../../runner.php';

use
	estvoyage\statsd\tests\units,
	mock\estvoyage\statsd as mockOfStatsd
;

class packet extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\statsd\metric')
		;
	}

	function testNewMetric()
	{
		$this
			->given(
				$metric = new mockOfStatsd\metric
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->newTestedInstance->newMetric($metric))->isTestedInstance
		;
	}

	function testStatsdMetricTemplateIs()
	{
		$this
			->given(
				$template = new mockOfStatsd\metric\template
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->statsdMetricTemplateIs($template))->isTestedInstance
				->mock($template)
					->receive('newStatsdMetric')
						->never

			->if(
				$metric = new mockOfStatsd\metric,

				$this->testedInstance
					->newMetric($metric)
						->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->once

			->if(
				$this->testedInstance->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->once

			->if(
				$this->testedInstance
					->newMetric($metric)
						->newMetric($metric)
							->statsdMetricTemplateIs($template)
			)
			->then
				->mock($template)
					->receive('newStatsdMetric')
						->withArguments($metric)
							->thrice
		;
	}
}
