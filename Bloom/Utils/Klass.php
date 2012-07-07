<?php

namespace Bloom\Utils;

class Klass
{
	/**
     * Let you get the name of a class from it's namespace
     * 
     * @param  object|string  $object An object or namespace to convert
     * @return mixed          The path to the class file
     */
	public static function getClassName($object)
	{
	    if (is_object($object) || is_string($object)) {
	        $class = explode('\\', (is_string($object) ? $object : get_class($object)));
			return $class[count($class) - 1];
	    }
	   
		return false;
	}

	/**
     * Let you get a path to class from it's namespace
     * 
     * @param  object|string  $object An object or namespace name to convert
     * @return mixed          The path to the class file
     */
    public static function getClassPath($object) {
		if (is_object($object) || is_string($object)) {
			$class = str_replace(['/', '\\'], DS, (is_string($object) ? $object : get_class($object)));
			return $class;
		}
        
        return false;
    }
}