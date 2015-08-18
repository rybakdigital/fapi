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
}
