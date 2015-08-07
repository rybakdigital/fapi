<?php

namespace Fapi\Component\Framework\Tests\Controller;

use \PHPUnit_Framework_TestCase as TestCase;
use Fapi\Component\Framework\Controller\Controller;
use Ucc\Data\Validator\Validator;

class ContrllerTest extends TestCase
{
    public function testGetValidator()
    {
        $controller = new Controller;
        $this->assertInstanceOf('Ucc\Data\Validator\ValidatorInterface', $controller->getValidator());
    }

    public function testSetValidator()
    {
        $controller     = new Controller;
        $validator      = new Validator;
        $this->assertInstanceOf(get_class($controller), $controller->setValidator($validator));
    }
}
