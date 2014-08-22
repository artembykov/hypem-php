<?php
namespace Hypem;

class Request
{
    const BASE_URI = 'http://hypem.com';

    private $curl;

    public function __construct()
    {
        $this->curl = new \Curl\Curl();
    }

    public function get($path)
    {
        return $this->curl->get(self::BASE_URI . $path);
    }

    public function getJson($path)
    {
        return json_decode($this->get($path), true);
    }
}
