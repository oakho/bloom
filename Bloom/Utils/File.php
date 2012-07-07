<?php

namespace Bloom\Utils;

class File
{
	public static function loadFile($path)
    {
        return include($path);
    }

    public static function getFileName($file)
    {
        return explode(".", $file)[0];
    }

    public static function getFileExt($file)
    {
		return explode(".", $file)[1];
    }
}