<?php

namespace Fapi\Component\Routing\Matcher;

use Symfony\Component\HttpFoundation\Request;
use Fapi\Component\Routing\RouteCollection;

/**
 * Fapi\Component\Routing\Matcher\MatcherInterface
 *
 * @author  Kris Rybak <kris@krisrybak.com>
 */
interface MatcherInterface
{
    /**
     * Matches current Request to Route
     */
    public function match(RouteCollection $routes, Request $request);
}
