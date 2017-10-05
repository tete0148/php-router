<?php

namespace tete0148\PHPRouter;

class Router {

    /**
     * @var array[Route]
     */
    private $routes = [];

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

    public function handle($url)
    {
        foreach($this->routes as $route) {
            /** @var Route $route */
            $route_url = $route->getUrl();

            foreach ($route->getRules() as $parameter => $rule) {
                $required = $route->getParameters()[$parameter] ? '?' : '';
                $route_url =
                    str_replace(
                        '{' . $parameter . $required . '}',
                        $required . '(' . $rule . ')' . $required,
                        $route_url
                    );
            }

            $matches = [];
            if($route_url === $url || preg_match($route_url, $url, $matches)) {
                unset($matches[0]);
                $matches = array_pad($matches, count($route->getParameters()), null);
                $parameters = array_combine($route->getParameters(), $matches);
                $callback = $route->getCallback();
                if(!is_callable($callback)) {
                    $callback = explode('@', $callback);
                    $callback = array_pad($callback, 2, 'index');
                    $callback[0] = new $callback[0];
                }

                call_user_func_array($callback, $parameters);
            }
            else
                continue;
        }

        return false;
    }
}