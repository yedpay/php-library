<?php

namespace Yedpay\Tests;

class HttpRequestTest extends \PHPUnit_Framework_TestCase
{
    public function test_get_info()
    {
        $http = $this->getMock('HttpRequest');
        $http->expects($this->any())
         ->method('setOption')
         ->will($this->returnValue('not JSON'));
        $this->setExpectedException('HttpResponseException');
    }
}
