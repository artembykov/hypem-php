<?php
namespace Hypem;

trait SingleRequest
{
    private $request;

    private function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = new Request();
        }
        return $this->request;
    }
}
