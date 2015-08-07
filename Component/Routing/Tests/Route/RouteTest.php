<?php

namespace Fapi\Component\Routing\Tests\Route;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Routing\Route\Route;
use \InvalidArgumentException;

class RouteTest extends TestCase
{
    public function routeProviderForPath()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll'),
                '/',
            ),
            array(
                $route = new Route('/products', array('GET'), 'Producs', 'get'),
                '/products',
            ),
            array(
                $route = new Route(null, array('POST'), 'Home', 'get'),
                '/',
            ),
        );
    }

    /**
     * @dataProvider routeProviderForPath
     */
    public function testGetPath($route, $expected)
    {
        $this->assertEquals($expected, $route->getPath());
    }

    public function pathProvider()
    {
        return array(
            array(
                null, '/',
            ),
            array(
                '/products', '/products',
            ),
            array(
                'some/path', '/some/path',
            ),
        );
    }

    /**
     * @dataProvider pathProvider
     */
    public function testSetPath($path, $expected)
    {
        $route = new Route(null, array('POST'), 'Home', 'get');
        $this->assertInstanceOf(get_class($route), $route->setPath($path));
        $this->assertEquals($expected, $route->getPath());
    }

    public function routeForMethodsProvider()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll'),
                array('GET', 'HEAD')
            ),
            array(
                $route = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
                array('GET', 'HEAD', 'POST')
            ),
            array(
                $route = new Route(null, array('PUT'), 'Home', 'get'),
                array('PUT')
            ),
        );
    }

    /**
     * @dataProvider routeForMethodsProvider
     */
    public function testGetMethods($route, $expected)
    {
        $this->assertEquals($expected, $route->getMethods());
    }

    public function methodProvider()
    {
        return array(
            array(
                'GET',
                array('GET', 'HEAD')
            ),
            array(
                'POST',
                array('POST')
            ),
            array(
                'PUT',
                array('PUT')
            ),
        );
    }

    /**
     * @dataProvider methodProvider
     */
    public function testAddMethod($method, $expected)
    {
        $route = new Route('/', array(), 'Orders', 'get');
        $this->assertInstanceOf(get_class($route), $route->addMethod($method));
        $this->assertEquals($expected, $route->getMethods());
    }

    public function badMethodProvider()
    {
        return array(
            array(
                'GETS',
            ),
            array(
                'POSTS',
            ),
            array(
                'PUTS',
            ),
        );
    }

    /**
     * @dataProvider            badMethodProvider
     * @expectedException       InvalidArgumentException
     */
    public function testAddMethodFail($method)
    {
        $route = new Route('/', array(), 'Orders', 'get');
        $this->assertInstanceOf(get_class($route), $route->addMethod($method));
    }

    public function routeForControllerProvider()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll'),
               'Orders'
            ),
            array(
                $route = new Route('/products', array('GET', 'POST'), 'Producs', 'get'),
                'Producs'
            ),
            array(
                $route = new Route(null, array('PUT'), 'Home', 'get'),
                'Home'
            ),
        );
    }

    /**
     * @dataProvider routeForControllerProvider
     */
    public function testGetController($route, $expected)
    {
        $this->assertEquals($expected, $route->getController());
    }

    public function controllerProvider()
    {
        return array(
            array(
                'Home'
            ),
            array(
                'Orders'
            ),
            array(
                'Products'
            ),
        );
    }

    /**
     * @dataProvider controllerProvider
     */
    public function testSetController($controller)
    {
        $route = new Route(null, array('POST'), 'Default', 'get');
        $this->assertInstanceOf(get_class($route), $route->setController($controller));
        $this->assertEquals($controller, $route->getController());
    }

    public function badControllerProvider()
    {
        return array(
            array(
                null
            ),
            array(
                ''
            ),
            array(
                false
            ),
        );
    }

    /**
     * @dataProvider            badControllerProvider
     * @expectedException       InvalidArgumentException
     */
    public function testSetControllerFail($controller)
    {
        $route = new Route(null, array('POST'), 'Default', 'get');
        $this->assertInstanceOf(get_class($route), $route->setController($controller));
    }

    public function routeForCallsProvider()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll'),
               'getAll'
            ),
            array(
                $route = new Route('/products', array('GET', 'POST'), 'Producs', 'setProduct'),
                'setProduct'
            ),
            array(
                $route = new Route(null, array('PUT'), 'Home', 'get'),
                'get'
            ),
        );
    }

    /**
     * @dataProvider routeForCallsProvider
     */
    public function testGetCalls($route, $expected)
    {
        $this->assertEquals($expected, $route->getCalls());
    }

    public function callableProvider()
    {
        return array(
            array(
                'get'
            ),
            array(
                'set'
            ),
            array(
                'all'
            ),
        );
    }

    /**
     * @dataProvider callableProvider
     */
    public function testSetCalls($callable)
    {
        $route = new Route(null, array('POST'), 'Default', 'get');
        $this->assertInstanceOf(get_class($route), $route->setCalls($callable));
        $this->assertEquals($callable, $route->getCalls());
    }

    public function badCallableProvider()
    {
        return array(
            array(
                null
            ),
            array(
                ''
            ),
            array(
                false
            ),
        );
    }

    /**
     * @dataProvider            badCallableProvider
     * @expectedException       InvalidArgumentException
     */
    public function testSetCallsFail($callable)
    {
        $route = new Route(null, array('POST'), 'Default', 'get');
        $this->assertInstanceOf(get_class($route), $route->setCalls($callable));
    }

    public function routeForRequirementsProvider()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll', array('orderSpec' => 'int')),
                array('orderSpec' => 'int')
            ),
            array(
                $route = new Route('/products', array('GET', 'POST'), 'Producs', 'setProduct', array('orderSpec' => 'int', 'productId' => 'int')),
                array('orderSpec' => 'int', 'productId' => 'int')
            ),
            array(
                $route = new Route(null, array('PUT'), 'Home', 'get'),
                array(),
            ),
        );
    }

    /**
     * @dataProvider routeForRequirementsProvider
     */
    public function testGetRequirements($route, $expected)
    {
        $this->assertEquals($expected, $route->getRequirements());
    }

    public function requirementsProvider()
    {
        return array(
            array(
                array('orderSpec' => 'int', 'productId' => 'int')
            ),
            array(
                array('orderSpec' => 'int', 'productId' => 'int', 'name' => 'str')
            ),
            array(
                array(),
            ),
        );
    }

    /**
     * @dataProvider requirementsProvider
     */
    public function testSetRequirements($requirements)
    {
        $route = new Route(null, array('POST'), 'Default', 'get');
        $this->assertInstanceOf(get_class($route), $route->setRequirements($requirements));
        $this->assertEquals($requirements, $route->getRequirements());
    }

    public function routeForRegexProvider()
    {
        return array(
            array(
                $route = new Route('/', array('GET'), 'Orders', 'getAll', array('orderSpec' => 'int'), '/^\/v2\/numbers\/get\/range$/'),
                '/^\/v2\/numbers\/get\/range$/'
            ),
            array(
                $route = new Route('/products', array('GET', 'POST'), 'Producs', 'setProduct', array('orderSpec' => 'int', 'productId' => 'int'), '/^\/v2\/name$/'),
                '/^\/v2\/name$/'
            ),
            array(
                $route = new Route(null, array('PUT'), 'Home', 'get'),
                null,
            ),
        );
    }

    /**
     * @dataProvider routeForRegexProvider
     */
    public function testGetRegex($route, $expected)
    {
        $this->assertEquals($expected, $route->getRegex());
    }
}
