<?php

namespace Yedpay\Tests\Response;

use Yedpay\Response\Response;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Class ResponseTest
 * @package Yedpay\Tests
 */
class ResponseTest extends TestCase
{
    protected $result;

    protected $successArray;

    protected $successData;

    protected $text = 'This is a message';

    protected $errorArray = [
        'success' => false,
        'message' => 'Unauthenticated',
        'status' => 401,
    ];

    public function set_up()
    {
        parent::set_up();
        $this->successData = [
            'obj1' => '1',
            'obj2' => '2',
            'obj3' => '3',
        ];
        $this->successArray = [
            'success' => true,
            'data' => $this->successData,
        ];

        $this->result = new Response($this->text);
    }

    public function test_class_exists()
    {
        $this->assertTrue(class_exists('Yedpay\Response\Response'));
    }

    public function test_exist_method_get_message()
    {
        $this->assertTrue(method_exists(Response::class, 'getMessage'));
    }

    public function test_exist_method_has_message()
    {
        $this->assertTrue(method_exists(Response::class, 'hasMessage'));
    }

    public function test_has_message()
    {
        $this->assertTrue($this->result->hasMessage());
    }

    public function test_get_message()
    {
        $this->assertEquals($this->text, $this->result->getMessage());
    }
}