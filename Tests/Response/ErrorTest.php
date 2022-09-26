<?php

namespace Yedpay\Tests\Response;

use Yedpay\Response\Error;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

class ErrorTest extends TestCase
{
    protected $result;

    protected $error = [
        'success' => false,
        'message' => 'Could not create resource',
        'status' => 422,
        'code' => 666,
        'errors' => [
            'code' => 422,
            'field' => 'phone',
            'message' => 'The phone must be a number',
        ]
    ];

    public function set_up()
    {
        parent::set_up();
        $this->result = new Error($this->error);
    }

    public function test_class_exists()
    {
        $this->assertTrue(class_exists(Error::class));
    }

    public function test_get_error_code_exists()
    {
        $this->assertTrue(method_exists(Error::class, 'getErrorCode'));
    }

    public function test_get_error_code()
    {
        $this->assertEquals($this->error['status'], $this->result->getErrorCode());
    }

    public function test_get_errors()
    {
        $this->assertEquals($this->error['errors'], $this->result->getErrors());
    }

    public function test_get_response_code()
    {
        $this->assertEquals($this->error['code'], $this->result->getResponseCode());
    }

}
