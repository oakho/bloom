<?php

namespace Bloom\Utils;

class Upload
{
	public static function file($path, $destination, callable $callback)
	{
		if(move_uploaded_file($path, $destination)) {
			$callback();
			return true;
		}

		return false;
	}
}