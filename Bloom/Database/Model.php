<?php

namespace Bloom\Database;

use Bloom\Database\Query;
use Bloom\Utils\Klass;

abstract class Model
{
    protected static $_stack = [];
    protected static $_name = null;
    protected static $_table = null;

    public function __construct(array $attributes = [])
	{
		foreach($attributes as $attribute => $value) {
			$this->{$attribute} = $value;
		}
		$this->created_at = date("Y-m-j H:i:s");
		$this->updated_at = date("Y-m-j H:i:s");
	}
}