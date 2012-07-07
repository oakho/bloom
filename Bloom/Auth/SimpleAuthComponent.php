<?php

namespace Bloom\Auth;

trait SimpleAuthComponent
{
	abstract protected function isAuthorized();

	public function callAction()
    {
        if($this->isAuthorized()) {
            $this->beforeAction();
            $this->{$this->action}();
            $this->afterAction();
        }
    }
}