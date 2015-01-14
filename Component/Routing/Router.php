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
            $this->collection = $this->loadRouteCollection($this->getResourse());
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

    public function loadResurce()
    {
        
    }

    public function loadRouteCollection($resource)
    {

    }
}
