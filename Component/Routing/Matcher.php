<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\Matcher\MatcherInterface;
use Fapi\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

/**
 * Fapi\Component\Routing\Matcher
 *
 * Matches Route for the request.
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
class Matcher implements MatcherInterface
{
    /**
     * @var Request
     */
    protected $request;


    /**
     * @var RouteCollection
     */
    protected $collection;

    /**
     * Matches current Request to Route
     */
    public function match(RouteCollection $collection, Request $request)
    {
        foreach ($collection as $route) {
            var_dump($route);
        }
    }
}
