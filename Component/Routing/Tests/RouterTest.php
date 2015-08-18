<?php

namespace Fapi\Component\Routing\Tests;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends TestCase
{
    public function routeProviderForParser()
    {
        return array(
            array(array(
                'path'          => '',
                'controller'    => 'Home',
                'methods'       => array('POST', 'PUT'),
                'calls'         => 'index',
            )),
            array(array(
                'path'          => '',
                'controller'    => 'Orders',
                'calls'         => 'index',
                'requirements'  => array('id' => 'int'),
            )),
        );
    }

    /**
     * @dataProvider routeProviderForParser
     */
    public function testParseRoute($routeSpec)
    {
        $router = new Router(new Request);
        $this->assertInstanceOf('Fapi\Component\Routing\Route\Route', $router->parseRoute($routeSpec));
    }

    public function resourceProvider()
    {
        return array(
            array(
                "fapi/Component/Routing/Tests/_resources/routing.yml",
                4
            ),
            array(
                "fapi/Component/Routing/Tests/_resources",
                4
            ),
            array(
                null,
                0
            ),
            array(
                "fapi/Component/Routing/Tests/_resources/routing.json",
                1
            ),
        );
    }

    /**
     * @dataProvider resourceProvider
     */
    public function testLoadResource($resource, $expectedCount)
    {
        $router = new Router(new Request);
        $routes = $router->loadResource($resource);
        $this->assertTrue(is_array($routes));
        $this->assertCount($expectedCount, $routes);
    }

    /**
     * @dataProvider resourceProvider
     */
    public function testloadRouteCollection($resource, $expectedCount)
    {
        $router = new Router(new Request);
        $routes = $router->loadRouteCollection($resource);
        $this->assertInstanceOf('Fapi\Component\Routing\RouteCollection', $routes);
        $this->assertEquals($expectedCount, $routes->count());
    }

    // public function testGetResource()
    // {
    //     $router = new Router(new Request);
    //     var_dump($router->getResourse());die;
    // }
}
