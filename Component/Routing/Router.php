<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;

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
    protected $requestContext;

    /**
     * @var mixed
     */
    protected $resource;

    public function resolveRoute()
    {
        $routes = $this->getRouteCollection();
    }

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
            $this->resource = $this->loadResurce();
        }

        return $this->resource;
    }

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

    public function loadRouteCollection()
    {
        $routes = array();

        $routes = $this->loadResurce();

        var_dump($routes);
    }

    public function loadResurce($resource = null)
    {
        $routes = array();

        $basePath = '../';

        if ($resource == null) {
            $resource = "app/config/routing.yml";
        }

        if (!(substr($resource, -3, 3) == 'yml')) {
            $resource = $resource . '/routing.yml';
        }

        if (file_exists($basePath . $resource)) {
            $file = file_get_contents($basePath . $resource);

            $array  = Yaml::parse($file);

            if (is_array($array)) {

                if (isset($array['imports'])) {
                    foreach ($array['imports'] as $import) {
                        if (isset($import['resource'])) {
                            foreach ($this->loadResurce($import['resource']) as $name => $route) {
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
}
