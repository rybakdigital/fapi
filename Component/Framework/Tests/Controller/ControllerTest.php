<?php

namespace Fapi\Component\Framework\Tests\Controller;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Framework\Controller\Controller;

class ContrllerTest extends TestCase
{
    public function testGetValidator()
    {
        $controller = new Controller;
        $this->assertInstanceOf('Ucc\Data\Validator\ValidatorInterface', $controller->getValidator());
    }
}
