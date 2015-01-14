<?php

namespace Fapi\Component\Routing;

interface RouterInterface
{
    /**
     * @return RouteInterface
     */
    public function resolveRoute();
}
