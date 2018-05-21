<?php

namespace Yedpay\Tests\Response;


use PHPUnit_Framework_TestCase;
use Yedpay\Response\Success;

/**
 * Class SuccessTest
 * @package Yedpay\Tests\Response
 */
class SuccessTest extends PHPUnit_Framework_TestCase
{
    protected $result;

    protected $response;

    protected $data;

    public function setUp()
    {
        $this->data = [
            'obj1' => '1',
            'obj2' => '2',
            'obj3' => '3',
        ];
        $this->response = [
            'success' => true,
            'message' => 'successful',
            'data' => $this->data,
        ];
        $this->result = new Success($this->response);
    }


    public function test_class_exists()
    {
        $this->assertTrue(class_exists(Success::class));
    }

    public function test_get_data_exists()
    {
        $this->assertTrue(method_exists(Success::class, 'getData'));
    }

    public function test_has_data_exists()
    {
        $this->assertTrue(method_exists(Success::class, 'hasData'));
    }

    public function test_has_data()
    {
        $this->assertTrue($this->result->hasData());
    }

    public function test_get_data()
    {
        $this->assertEquals($this->data, $this->result->getData());
    }

}