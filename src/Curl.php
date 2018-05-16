<?php

namespace Yedpay;

use Yedpay\Curl\Request;
use Exception;

class Curl
{
    /**
     * call Yedpay API using Curl
     *
     * @param mixed $path
     * @param mixed $method
     * @param mixed $parameters
     * @return void
     */
    public function call($path, $method, $parameters)
    {
        $library = new Library();
        $url = $library->getEndpoint() . $path;
        $token = $library->getAccessToken();

        try {
            $curl = new Request();
            $curl->setOptionArray($url, $method, $parameters, $token);
            $result = $curl->execute();
            $err = $curl->error();
            $curl->close();

            if ($err) {
                throw new Exception('Error Processing Request: ' . $err);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $result;
    }
}
