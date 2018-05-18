<?php

namespace Yedpay;

use Yedpay\Curl\Request;
use Exception;

class Curl
{
    protected $library;
    protected $request;

    /**
     * Get the value of library
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * Set the value of library
     *
     * @return  self
     */
    public function setLibrary(Library $library)
    {
        $this->library = $library;

        return $this;
    }

    /**
     * Get the value of request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set the value of request
     *
     * @return  self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

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
        $library = $this->getLibrary();
        $url = $library->getEndpoint() . $path;
        $token = $library->getAccessToken();

        try {
            $curl = $this->getRequest();
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
