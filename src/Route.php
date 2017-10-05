<?php

namespace tete0148\PHPRouter;

class Route {

    private $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE'];
    private $method = 'GET';
	private $url = '/';
	private $name = null;
	private $parameters = [];
	private $rules = [];
	private $callback;

    /**
     * Route constructor.
     * @param $method string
     * @param $url string
     */
	public function __construct($method, $url, $callback)
    {
        if(!in_array($method,$this->allowed_methods))
            throw new \InvalidArgumentException('Invalid HTTP method: ' . $method);

        $this->setMethod($method);
        $this->setUrl($url);
        $this->setCallback($callback);
    }

    /**
     * @return string
     */
    private function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    private function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    private function setUrl($url)
    {
        $matches = [];
        preg_match_all('/{([A-Za-z0-9_?]+)}/', $url, $matches);

        foreach($matches[1] as $match) {
                $this->parameters[str_replace('?', '', $match)] = strpos($match,'?');
        }

        foreach ($this->parameters as $parameter => $required)
            if(!isset($this->rules[$parameter]))
                $this->rules[$parameter] = '[A-Za-z0-9-_]+';

        $url = '@^' . $url . '$@';

        $this->url = $url;
    }

    /**
     * @return string
     */
    private function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     * @return $this
     */
    public function setRules($rules)
    {
        foreach($rules as $parameter => $rule)
            $this->rules[$parameter] = $rule;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return callable
     */
    public function getCallback()
    {
        return $this->callback;
    }

    public function setCallback($callback)
    {
        $this->callback = $callback;
    }
}