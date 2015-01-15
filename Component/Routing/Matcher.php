<?php

namespace Fapi\Component\Routing;

use Fapi\Component\Routing\Matcher\MatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Fapi\Component\Routing\RouteCollection;

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
    protected $routes;

    /**
     * Matches current Request to Route
     */
    public function match(RouteCollection $routes, Request $request)
    {

    }
}
