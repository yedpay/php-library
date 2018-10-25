<?php

namespace Yedpay;

use Yedpay\Curl\Request;
use Exception;
use Yedpay\Response\Error;
use Yedpay\Response\Success;
use Yedpay\Response\Response;

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
     * @param Library $library
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
     * @param Request $request
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
     * @return Response
     */
    public function call($path, $method, $parameters = [])
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
                return new Error([
                    'status' => 400,
                    'message' => 'Error Parsing Request: ' . $err,
                ]);
            }

            if ($result !== null) {
                $result = json_decode($result);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return new Error([
                        'status' => 500,
                        'message' => 'Error Parsing Response: ' . json_last_error_msg(),
                    ]);
                }

                if ($result->success) {
                    return new Success((array)$result);
                }
            }

            return new Error((array) $result);
        } catch (Exception $e) {
            return new Error([
                'status' => 500,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
