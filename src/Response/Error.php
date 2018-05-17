<?php

namespace Yedpay\Response;

/**
 * Class Error
 * @package Yedpay\Response
 */
class Error extends Response
{
    private $errorCode;

    private $errors;

    /**
     * Error constructor.
     * @param $response
     */
    public function __construct($response)
    {
        $this->errorCode = $response['status'];
        if (!empty($response['message'])) {
            parent::__construct($response['message']);
        }
        if (!empty($response['errors'])) {
            $this->errors = $response['errors'];
        }
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

}