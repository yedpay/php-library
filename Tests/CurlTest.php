<?php

namespace Yedpay\Tests;

use Yedpay\Curl;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->class = new Curl();
    }

    public function tearDown()
    {
        $this->class = null;
    }

    public function test_exist_class()
    {
        $this->assertTrue($this->class instanceof Curl);
    }

    public function test_exist_method_call()
    {
        $this->assertTrue(method_exists($this->class, 'call'));
    }

    public function test_call()
    {
        $parameters = [
                'gateway_id' => 1,
                'currency' => 'HKD',
                'amount' => 0.1,
                'wallet' => 'CN',
            ];
        $path = '/precreate/4ROOGORJ';
        $method = 'POST';

        $expected = $this->getTestResult();

        $http = $this->getMockBuilder('HttpRequest')
                        ->setMethods(['execute'])
                        ->getMock();
        $http->method('execute')
           ->willReturn($expected);

        $this->assertEquals($http->execute(), $this->class->call($path, $method, $parameters));
    }

    public function test_call_exception()
    {
        $parameters = 'string';
        $path = '/precreate';
        $method = 'POST';

        $http = $this->getMockBuilder('HttpRequest')
                        ->setMethods(['execute'])
                        ->getMock();
        $http->method('execute')
           ->willReturn('http_build_query(): Parameter 1 expected to be Array or Object.  Incorrect value given');

        $this->assertEquals($http->execute(), $this->class->call($path, $method, $parameters));
    }

    protected function getTestResult()
    {
        return json_encode([
            'success' => false,
            'message' => 'Unauthenticated.',
            'status' => 401
        ]);
    }
}
