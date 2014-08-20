<?php
namespace Hypem;

class Request
{
    const BASE_URI = 'http://api.hypem.com';

    private $curl;

    public function __construct()
    {
        $this->curl = new \Curl\Curl();
    }

    public function get($path)
    {
        $this->curl->get(self::BASE_URI . $path);
        return $this->curl->raw_response;
    }

    public function getJson($path)
    {
        $response = json_decode($this->get($path), true);
        unset($response['version']);
        return $response;
    }
}
