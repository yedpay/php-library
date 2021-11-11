<?php

namespace Yedpay\Tests;

use Yedpay\Curl\Request;
use Yedpay\Curl\HttpRequest;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class RequestTest extends TestCase
{
    protected $path;
    protected $token;
    protected $parameters;
    protected $method;

    protected $request;

    public function set_up()
    {
        $this->path = null;
        $this->token = 'abcdefg';
        $this->parameters = [
                'gateway_id' => 1,
                'currency' => 'HKD',
                'amount' => 0.1,
                'wallet' => 'CN',
            ];
        $this->method = 'POST';

        $this->request = new Request();
    }

    public function tear_down()
    {
        $this->path = null;
        $this->token = null;
        $this->parameters = null;
        $this->method = null;

        $this->request = null;
    }

    public function test_exist_class()
    {
        $this->assertTrue($this->request instanceof HttpRequest);
    }

    public function test_execute()
    {
        $this->request->setOptionArray($this->path, $this->method, $this->parameters, $this->token, true);
        $actual = $this->request->execute();
        $this->assertEquals(null, $actual);
    }

    public function test_error()
    {
        $actual = $this->request->error();
        $this->assertEquals(null, $actual);
    }

    public function test_close()
    {
        $actual = $this->request->close();
        $this->assertEquals(null, $actual);
    }
}
