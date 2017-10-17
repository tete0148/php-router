<?php

namespace tete0148\PHPRouter;

class Router {

    private $app;
    /**
     * @var array[Route]
     */
    private $routes = [];

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param $method string
     * @param $url string
     * @param $callback callable|string
     * @return Route
     */
    public function addRoute(string $method, string $url, $callback) : Route
    {
        $route = new Route($method, $url, $callback);

        $this->routes[] = $route;

        return $route;
    }

    /**
     * Returns the http path for the given route
     *
     * @param string $route_name
     * @param array $params
     */
    public function pathFor(string $route_name, $params = [])
    {
        $url = null;
        foreach($this->routes as $route)
            /** @var Route $route */
            if($route->getName() === $route_name) {
                $route_params = $route->getParameters();

                $url = $route->getUrl();
                foreach($route_params as $route_param => $required)
                    $url = preg_replace('/\{' . $route_param . '\??\}/',
                        $params[$route_param] ?? null, $url);

            }
        return ($this->app->getBaseUrl() ?? '') . rtrim($url,'/');
    }

    public function handle($url)
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach($this->routes as $route) {
            /** @var Route $route */

            if($route->getMethod() !== $method)
                continue;

            $route_regex = $route->getRegex();

            foreach ($route->getRules() as $parameter => $rule) {
                $required = $route->getParameters()[$parameter] ? '?' : '';
                $route_regex =
                    str_replace(
                        '{' . $parameter . $required . '}',
                        $required . '(' . $rule . ')' . $required,
                        $route_regex
                    );
            }

            $matches = [];
            if($route_regex === $url || preg_match($route_regex, $url, $matches)) {
                unset($matches[0]);
                $matches = array_pad($matches, count($route->getParameters()), null);
                $parameters = array_combine($route->getParameters(), $matches);
                $callback = $route->getCallback();
                if(!is_callable($callback)) {
                    $callback = explode('@', $callback);
                    $callback = array_pad($callback, 2, 'index');
                    $callback[0] = new $callback[0]($this->app);
                }

                call_user_func_array($callback, $parameters);
                break;
            }
            else
                continue;
        }

        return false;
    }
}