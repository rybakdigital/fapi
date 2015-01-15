<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\RouterInterface;
use Fapi\Component\Routing\Matcher;
use Fapi\Component\Routing\Route\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;

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
     * @var mixed
     */
    protected $resource;

    public function __construct(Request $request)
    {
        $this->matcher = new Matcher();
        $this->request = $request;
    }

    public function resolveRoute()
    {
        $this
            ->matcher
                ->match($this->getRouteCollection(), $this->request);
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
        $collection = new RouteCollection();

        $routes = $this->loadResurce();

        foreach ($routes as $name => $route) {
            $collection->add($name, new Route);
        }

        return $collection;
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
