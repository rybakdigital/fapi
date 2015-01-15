<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\Route\Route;

/**
 * Fapi\Component\Routing\Router\RouteCollection
 *
 * This class provides methods to store and manipulate collection of Route objects
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class RouteCollection
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * Gets the number of Routes in this collection.
     *
     * @return int The number of routes
     */
    public function count()
    {
        return count($this->routes);
    }

    /**
     * Adds a route.
     *
     * @param string $name  The route name
     * @param Route  $route A Route instance
     * @return RouteCollection
     */
    public function add($name, Route $route)
    {
        unset($this->routes[$name]);
        $this->routes[$name] = $route;

        return $this;
    }

    /**
     * Gets a route by name.
     *
     * @param string $name The route name
     *
     * @return Route|null A Route instance or null when not found
     */
    public function get($name)
    {
        return isset($this->routes[$name]) ? $this->routes[$name] : null;
    }

    /**
     * Removes a route by name from the collection.
     *
     * @param string $name The Route name
     * @return RouteCollection
     */
    public function remove($name)
    {
        if (isset($this->routes[$name])) {
            unset($this->routes[$name]);
        }

        return $this;
    }
}
