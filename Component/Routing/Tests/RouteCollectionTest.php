<?php

namespace Fapi\Component\Routing\Tests\Route;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Routing\Route\Route;
use Fapi\Component\Routing\RouteCollection;

class RouteCollectionTest extends TestCase
{
    public function routeCollectionProviderForTestCount()
    {
        $routeCollection = new RouteCollection;
        $routes = array(
            $route = new Route('/', array('GET'), 'Orders', 'getAll'),
            $route = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
            $route = new Route(null, array('PUT'), 'Home', 'getHome'),
        );

        foreach ($routes as $route) {
            $routeCollection->add($route->getCalls(), $route);
        }

        return array(
            array($routeCollection, 3),
            array(new RouteCollection, 0),
        );
    }

    /**
     * @dataProvider routeCollectionProviderForTestCount
     */
    public function testCount($routeCollection, $expected)
    {
        $this->assertEquals($expected, $routeCollection->count());
    }

    public function routeProvider()
    {
        return array(
            array($route = new Route('/', array('GET'), 'Orders', 'getAll')),
            array($route = new Route('/products', array('GET', 'POST'), 'Producs', 'get')),
            array($route = new Route(null, array('PUT'), 'Home', 'getAll')),
        );
    }

    /**
     * @dataProvider routeProvider
     */
    public function testAdd($route)
    {
        $routeCollection = new RouteCollection;
        $this->assertInstanceOf(get_class($routeCollection), $routeCollection->add($route->getCalls(), $route));
        $expectedMethods = $routeCollection->get($route->getCalls())->getMethods();
        $this->assertSame($route->getMethods(), $expectedMethods);
    }
}
