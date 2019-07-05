<?php

namespace Yedpay\Tests;

use Yedpay\Library;

class YedpayTest extends \PHPUnit_Framework_TestCase
{
    protected $class = null;

    public function setUp()
    {
        $this->class = new Library();
    }

    public function test_exist_class()
    {
        $this->assertTrue($this->class instanceof Library);
    }

    public function test_exist_method_get_production_in_library()
    {
        $this->assertTrue(method_exists($this->class, 'getProduction'));
    }

    public function test_exist_method_set_production_in_library()
    {
        $this->assertTrue(method_exists($this->class, 'setProduction'));
    }

    public function test_set_production_return_instance_of_library()
    {
        $result = $this->class->setProduction('string');
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_production_return_string_if_not_setted()
    {
        $this->assertFalse(is_null($this->class->getProduction()));
    }

    public function test_set_staging_return_instance_of_library()
    {
        $result = $this->class->setStaging('string');
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_staging_return_string_if_not_setted()
    {
        $this->assertFalse(is_null($this->class->getStaging()));
    }

    public function test_set_version_return_instance_of_library()
    {
        $result = $this->class->setVersion('string');
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_version_return_string_if_not_setted()
    {
        $this->assertFalse(is_null($this->class->getVersion()));
    }

    public function test_set_access_token_return_instance_of_library()
    {
        $result = $this->class->setAccessToken('string');
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_access_token_return_null_if_not_setted()
    {
        $this->assertTrue(is_null($this->class->getAccessToken()));
    }

    public function test_get_access_token_return_value_if_setted()
    {
        $value = 'string';
        $result = $this->class->setAccessToken($value);
        $this->assertTrue($this->class->getAccessToken() == $value);
    }

    public function test_set_api_key_return_instance_of_library()
    {
        $result = $this->class->setApiKey('string');
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_api_key_return_null_if_not_setted()
    {
        $this->assertTrue(is_null($this->class->getApiKey()));
    }

    public function test_get_api_key_return_value_if_setted()
    {
        $value = 'string';
        $result = $this->class->setApiKey($value);
        $this->assertTrue($this->class->getApiKey() == $value);
    }

    public function test_set_environment_not_accept_different_of_staging_or_production()
    {
        $this->setExpectedException("\Exception");
        $this->class->setEnvironment('string');
    }

    public function test_set_environment_success()
    {
        $result = $this->class->setEnvironment(Library::STAGING);
        $this->assertTrue($result instanceof Library);
    }

    public function test_get_environment_return_string_if_not_setted()
    {
        $this->assertTrue(!is_null($this->class->getEnvironment()));
    }

    public function test_get_Endpoint()
    {
        $this->assertTrue(!is_null($this->class->getEndpoint()));
    }
}
