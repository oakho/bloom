<?php

namespace Bloom\Utils;

class Folder
{
	public static function iterateFilesOfFolder($path, callable $callback)
	{
		if($handle = opendir($path)) {
            while (($entry = readdir($handle)) !== false) {
                if ($entry != "." && $entry != "..") {
                    call_user_func($callback, $entry);
                }
            }
            
            closedir($handle);

            return true;
        } else {
            throw new \Exception("Can't open folder : {$path}");
        }
	}
}