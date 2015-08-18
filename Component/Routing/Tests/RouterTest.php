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
        );
    }

    /**
     * @dataProvider resourceProvider
     */
    public function testLoadResurce($resource, $expectedCount)
    {
        $router = new Router(new Request);
        $routes = $router->loadResurce($resource);
        $this->assertTrue(is_array($routes));
        $this->assertCount($expectedCount, $routes);
    }
}
