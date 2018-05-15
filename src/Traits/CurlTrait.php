<?php

namespace Yedpay\Traits;

trait CurlTrait
{
    public function setOptionArray($url, $method, $parameters, $token)
    {
        return [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => !empty($parameters) ? http_build_query($parameters) : null,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/x-www-form-urlencoded',
            ]
        ];
    }

    public function call($optionArray)
    {
        $curl = curl_init();

        curl_setopt_array($curl, $optionArray);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new \Exception('Error Processing Request: ' . $err);
        }

        return $response;
    }

    // private $handle = null;

    // public function setInit($url)
    // {
    //     return curl_init($url);
    // }

    // public function setOption($init, $array)
    // {
    //     curl_setopt_array($init, [
    //         CURLOPT_URL => $url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => '',
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30000,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => $method,
    //         CURLOPT_POSTFIELDS => !empty($parameters) ? http_build_query($parameters) : null,
    //         CURLOPT_HTTPHEADER => [
    //             'Authorization: Bearer ' . $token,
    //             'Content-Type: application/x-www-form-urlencoded',
    //         ],
    //     ]);
    // }

    // public function execute($init)
    // {
    //     return curl_exec($init);
    // }

    // public function getInfo($init, $name)
    // {
    //     return curl_getinfo($init, $name);
    // }

    // public function close($init)
    // {
    //     curl_close($init);
    // }

    // public function __construct($url)
    // {
    //     $this->handle = curl_init($url);
    // }

    // public function setOption($name, $value)
    // {
    //     curl_setopt($this->handle, $name, $value);
    // }

    // public function execute()
    // {
    //     return curl_exec($this->handle);
    // }

    // public function getInfo($name)
    // {
    //     return curl_getinfo($this->handle, $name);
    // }

    // public function close()
    // {
    //     curl_close($this->handle);
    // }
}
