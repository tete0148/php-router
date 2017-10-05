<?php

namespace tete0148\PHPRouter;

class App {

    private $router = null;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function run() : void
    {
        $url = $_SERVER['REQUEST_URI'];

        $this->router->handle($url);
    }

    public function get($url, $callback) : Route
    {
        return $this->router->addRoute('GET', $url, $callback);
    }

    public function post($url, $callback) : Route
    {
        return $this->router->addRoute('POST', $url, $callback);
    }

    public function put($url, $callback) : Route
    {
        return $this->router->addRoute('PUT', $url, $callback);
    }

    public function delete($url, $callback) : Route
    {
        return $this->router->addRoute('DELETE', $url, $callback);
    }

}