<?php

namespace Bloom\Http;

class Request
{
    /**
     * Hold URI
     * @var string
     */
	protected $_uri = null;

    /**
     * Hold request method
     * @var string
     */
    protected $_method = null;

    /**
     * Hold request headers
     * @var array
     */
    protected $_headers = [];

    /**
     * Hold request's routings parameters
     * @var array
     */
    protected $_routings = [];

    /**
     * Hold request's parameters
     * @var [type]
     */
    protected $_params = [];

    /**
     * Initialize the request to be parsed throught the Router
     */
	public function __construct()
    {
        $this->_uri = filter_var($_SERVER["REQUEST_URI"], FILTER_SANITIZE_STRING);
        $this->_method = $_SERVER["REQUEST_METHOD"];
        $this->_headers = apache_request_headers();

        if($this->isPost()) {
            $this->_params = array_merge($this->_params, $_POST);
        }
    }

    /**
     * Say if the request is GET
     * 
     * @return boolean 
     */
    public function isGet()
    {
        return ($this->_method === "GET");
    }

    /**
     * Say if the request is POST
     * 
     * @return boolean 
     */
    public function isPost()
    {
        return ($this->_method === "POST");
    }

    /**
     * Get the request's URI
     * 
     * @return string The URI
     */
    public function getURI()
    {
        return $this->_uri;
    }

    /**
     * Get the request's headers
     * 
     * @return array An array with headers
     */
    public function getHeaders()
    {
        return $this->_headers;
    }

    /**
     * Get routings of the request
     *
     * @return array An array with routings
     */
    public function getRoutings()
    {
        return $this->_routings;
    }
    
    /**
     * Set routings of the request
     *
     * @param  array $routings An array of routings to set
     * @return void
     */
    public function setRoutings($routings)
    {
        $this->_routings = array_merge($this->_routings, $routings);
    }

    /**
     * Get params of the request
     *
     * @return array An array with the params
     */
    public function getParams()
    {
        return $this->_params;
    }
    
    /**
     * Set params of the request
     *
     * @param  array $params An array of params to set
     * @return void
     */
    public function setParams($params)
    {
        $this->_params = array_merge($this->_params, $params);
    }
}

