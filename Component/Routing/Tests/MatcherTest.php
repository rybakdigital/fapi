<?php

namespace Fapi\Component\Routing\Tests;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Routing\Matcher;

class MatcherTest extends TestCase
{
    public function regexProvider()
    {
        return array(
            array('int', '\d+'),
            array('str', '\w+'),
            array('something', ''),
        );
    }

    /**
     * @dataProvider regexProvider
     */
    public function testGetRegexOperand($regex, $expected)
    {
        $matcher    = new Matcher;
        $reflector  = new \ReflectionClass($matcher);

        $method = $reflector->getMethod('getRegexOperand');
        $method->setAccessible(true);
        $output = $method->invoke($matcher, $regex);
        $this->assertEquals($expected, $output);
    }
}
