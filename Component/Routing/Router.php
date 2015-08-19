<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\RouterInterface;
use Fapi\Component\Routing\Matcher;
use Fapi\Component\Routing\Route\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Ucc\File\Path\Path;

/**
 * Fapi\Component\Routing\Router
 *
 * This class encapsulates routing system.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class Router implements RouterInterface
{
    /**
     * @var Fapi\Component\Routing\Matcher
     */
    protected $matcher;

    /**
     * @var Fapi\Component\Routing\RouteCollection
     */
    protected $collection;

    /**
     * @var Fapi\Component\Routing\RequestContext
     */
    protected $request;

    /**
     * @var array
     */
    protected $resource;

    /**
     * Array of known file types we can load resource from
     */
    public static $knownSourceTypes = array('yml', 'json');

    public function __construct(Request $request)
    {
        $this->matcher = new Matcher();
        $this->request = $request;
    }

    /**
     * Resolves Route
     *
     * @return Route
     */
    public function resolveRoute()
    {
        return $this
            ->matcher
                ->match($this->getRouteCollection(), $this->request);
    }

    /**
     * Gets RouteCollection
     *
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        if (null === $this->collection) {
            $this->collection = $this->loadRouteCollection();
        }

        return $this->collection;
    }

    public function getResourse()
    {
        if (null === $this->resource) {
            $this->resource = $this->loadResource();
        }

        return $this->resource;
    }

    /**
     * Resolves path slashes
     *
     * @param   string      $path   Path to resource
     * @return  string
     */
    private function resolveFilePathSlashes($path)
    {
        if (false !== $lastPos = strrpos($path, '/')) {
            if (($lastPos + 1) != strlen($path)) {
                $path = $path.'/';
            }
        }

        if (0 == $firstPos = strpos($path, '/')) {
            $path = substr($path, 1);
        }
        
        return $path;
    }

    /**
     * Loads RouteCollection from resource
     *
     * @param   string      $resource   Path to resource
     * @return  RouteCollection
     */
    public function loadRouteCollection($resource = null)
    {
        $collection = new RouteCollection();

        $routes = $this->loadResource($resource);

        foreach ($routes as $name => $route) {
            $collection->add($name, $this->parseRoute($route));
        }

        return $collection;
    }

    /**
     * Loads array of routes from resource
     *
     * @param   string      $resource   Path to resource
     * @return  array       Array of routes
     */
    public function loadResource($resource = null)
    {
        $routes = array();

        $basePath = '../';

        if ($resource == null) {
            $resource = "app/config/routing.yml";
        }

        $extension = Path::getExtension($resource);

        if (!in_array($extension, array('yml', 'json'))) {
            $resource = $resource . '/routing.yml';
            $extension = 'yml';
        }

        if (file_exists($basePath . $resource)) {
            $file = file_get_contents($basePath . $resource);

            if ($extension == 'yml') {
                $array  = Yaml::parse($file);
            } elseif ($extension == 'json') {
                $array  = json_decode($file, true);
            } else {
                throw new Exception("Unsupported routing file type. Routes can only be loaded from yml or json files");
            }

            if (is_array($array)) {

                if (isset($array['imports'])) {
                    foreach ($array['imports'] as $import) {
                        if (isset($import['resource'])) {
                            foreach ($this->loadResource($import['resource']) as $name => $route) {
                                $routes[$name] = $route;
                            }
                        }
                    }
                }

                if (isset($array['routes'])) {
                    foreach ($array['routes'] as $name => $route) {
                        $routes[$name] = $route;
                    }
                }
            }
        }

        return $routes;
    }

    /**
     * Parses array of spec into Route object
     *
     * @param   array   Route spec
     * @return  Route
     */
    public function parseRoute($routeSpec)
    {
        $path           = isset($routeSpec['path']) ? $routeSpec['path'] : null;
        $methods        = isset($routeSpec['methods']) ? $routeSpec['methods'] : array();
        $controller     = isset($routeSpec['controller']) ? $routeSpec['controller'] : null;
        $calls          = isset($routeSpec['calls']) ? $routeSpec['calls'] : null;
        $requirements   = isset($routeSpec['requirements']) ? $routeSpec['requirements'] : array();
        $regex          = isset($routeSpec['path']) ? $routeSpec['path'] : null;

        $route = new Route($path, $methods, $controller, $calls, $requirements, $regex);

        return $route;
    }
}
