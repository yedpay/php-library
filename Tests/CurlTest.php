<?php

namespace Yedpay\Tests;

use Yedpay\Curl;
use Yedpay\Library;
use Yedpay\Curl\Request;
use Exception;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    protected $endpoint;
    protected $path;
    protected $token;
    protected $parameters;
    protected $method;

    protected $class;
    protected $library;
    protected $http;
    protected $request;

    public function setUp()
    {
        $this->endpoint = 'https://api-staging.yedpay.com';
        $this->path = '/precreate/7V57';
        $this->token = 'abcdefg';
        $this->parameters = [
                'gateway_id' => 1,
                'currency' => 'HKD',
                'amount' => 0.1,
                'wallet' => 'CN',
            ];
        $this->method = 'POST';

        $this->class = new Curl();
        $this->library = $this->mockLibrary();
        $this->http = $this->mockHttp();
        $this->request = $this->getMockBuilder(Request::class)
                        ->setMethods(['setOptionArray', 'execute', 'error', 'close'])
                        ->getMock();
    }

    public function tearDown()
    {
        $this->endpoint = null;
        $this->path = null;
        $this->token = null;
        $this->parameters = null;
        $this->method = null;

        $this->class = null;
        $this->library = null;
        $this->http = null;
        $this->request = null;
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
        $request = $this->request;
        $request->method('execute')
            ->willReturn($this->http->execute());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertEquals($this->getTestResult(), $actual);
    }

    public function test_call_error()
    {
        $request = $this->request;
        $request->method('error')
            ->willReturn($this->http->error());

        $actual = $this->class
            ->setLibrary($this->library)
            ->setRequest($request)
            ->call($this->path, $this->method, $this->parameters);
        $this->assertEquals('Error Processing Request: ' . $this->getTestResult(false), $actual);
    }

    public function test_call_exception()
    {
        $request = $this->request;
        $request->method('execute')
                ->will($this->throwException(new Exception('string')));

        $actual = $this->class
                ->setLibrary($this->library)
                ->setRequest($request)
                ->call($this->path, $this->method, $this->parameters);

        $this->assertEquals('string', $actual);
    }

    protected function mockLibrary()
    {
        $library = $this->getMockBuilder(Library::class)
                        ->setMethods(['getEndpoint', 'getAccessToken'])
                        ->getMock();
        $library->method('getEndpoint')
            ->willReturn($this->endpoint);
        $library->method('getAccessToken')
            ->willReturn($this->token);
        return $library;
    }

    protected function mockHttp()
    {
        $http = $this->getMockBuilder('HttpRequest')
                        ->setMethods(['execute', 'error'])
                        ->getMock();
        $http->method('execute')
            ->willReturn($this->getTestResult());
        $http->method('error')
            ->willReturn($this->getTestResult(false));
        return $http;
    }

    protected function getTestResult($type = true)
    {
        if ($type) {
            return json_encode([
                'success' => false,
                'message' => 'Unauthenticated.',
                'status' => 401
            ]);
        }
        return 'http_build_query(): Parameter 1 expected to be Array or Object.  Incorrect value given';
    }
}
