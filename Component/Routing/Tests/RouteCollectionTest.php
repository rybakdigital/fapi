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

    public function routeProviderForAddFail()
    {
        return array(
            array(new \StdClass()),
            array(array()),
            array('route'),
            array(123),
        );
    }

    /**
     * @dataProvider            routeProviderForAddFail
     */
    public function testAddFail($route)
    {
        $this->setExpectedException(get_class(new \PHPUnit_Framework_Error("",0,"",1)));
        $routeCollection = new RouteCollection;
        $this->assertInstanceOf(get_class($routeCollection), $routeCollection->add('test', $route));
    }

    public function routeCollectionProviderForGet()
    {
        $routeCollection = new RouteCollection;
        $routes = array(
            $route1 = new Route('/', array('GET'), 'Orders', 'getAll'),
            $route2 = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
            $route3 = new Route(null, array('PUT'), 'Home', 'getHome'),
        );

        foreach ($routes as $route) {
            $routeCollection->add($route->getCalls(), $route);
        }

        return array(
            array($routeCollection, 'getAll', $route1),
            array($routeCollection, 'get', $route2),
            array($routeCollection, 'getHome', $route3),
            array($routeCollection, 'someRouteThatDoesNotExist', null),
        );
    }

    /**
     * @dataProvider routeCollectionProviderForGet
     */
    public function testGet($routeCollection, $name, $expected)
    {
        $this->assertSame($routeCollection->get($name), $expected);
    }

    public function routeCollectionProviderForRemove()
    {
        $routeCollection = new RouteCollection;
        $routes = array(
            $route1 = new Route('/', array('GET'), 'Orders', 'getAll'),
            $route2 = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
            $route3 = new Route(null, array('PUT'), 'Home', 'getHome'),
        );

        foreach ($routes as $route) {
            $routeCollection->add($route->getCalls(), $route);
        }

        return array(

        );
    }

    public function testRemove()
    {
        $routeCollection = new RouteCollection;
        $routes = array(
            $route1 = new Route('/', array('GET'), 'Orders', 'getAll'),
            $route2 = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
            $route3 = new Route(null, array('PUT'), 'Home', 'getHome'),
        );

        foreach ($routes as $route) {
            $routeCollection->add($route->getCalls(), $route);
        }

        // First check route exists
        $this->assertSame($routeCollection->get('getAll'), $route1);
        // Remove route
        $this->assertInstanceOf(get_class($routeCollection), $routeCollection->remove('getAll'));
        // Confirm route is gone
        $this->assertSame($routeCollection->get('getAll'), null);
    }

    public function testAll()
    {
        $routeCollection = new RouteCollection;
        $expected = array();
        $routes = array(
            $route1 = new Route('/', array('GET'), 'Orders', 'getAll'),
            $route2 = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
            $route3 = new Route(null, array('PUT'), 'Home', 'getHome'),
        );

        foreach ($routes as $route) {
            $routeCollection->add($route->getCalls(), $route);
            $expected[$route->getCalls()] = $route;
        }

        $this->assertSame($routeCollection->all(), $expected);
    }
}
