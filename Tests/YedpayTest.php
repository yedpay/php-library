<?php

namespace Yedpay\Tests;

use Yedpay\Library;

class YedpayTest extends \PHPUnit_Framework_TestCase
{
    public function test_exist_class()
    {
        $class = new Library();
        $this->assertTrue($class instanceof Library);
    }

    public function test_exist_method_get_production_in_library()
    {
        $class = new Library();
        $this->assertTrue(method_exists($class, 'getProduction'));
    }

    public function test_exist_method_set_production_in_library()
    {
        $class = new Library();
        $this->assertTrue(method_exists($class, 'setProduction'));
    }

    public function test_set_production_return_instance_of_library()
    {
        $class = new Library();
        $result = $class->setProduction('string');
        $this->assertTrue($result instanceof Library);
    }
}
