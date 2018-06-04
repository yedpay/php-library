<?php

namespace Yedpay\Curl;

interface HttpRequest
{
    public function setOptionArray($url, $method, $parameters, $token);

    public function execute();

    public function error();

    public function close();
}
