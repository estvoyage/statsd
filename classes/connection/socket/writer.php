<?php

namespace seshat\statsd\connection\socket;

use
	seshat\statsd\world\connection\socket
;

class writer implements socket\writer
{
	function writeOnResource($data, $resource)
	{
		while ($data)
		{
			$bytesWritten = fwrite($resource, $data);

			if ($bytesWritten === false)
			{
				throw new writer\exception('Unable to write \'' . $data . '\'');
			}

			$data = substr($data, $bytesWritten);
		}

		return $this;
	}
}
