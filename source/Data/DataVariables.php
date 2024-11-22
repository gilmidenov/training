<?php

namespace source\Data;

class RequestVariables
{
    private $get;
    private $post;
    private $request_type;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request_type = $_SERVER['REQUEST_METHOD'];
    }


    public function __get($name)
    {
        if (isset($this->post[$name])) {
            return $this->post[$name];
        } elseif (isset($this->get[$name])) {
            return $this->get[$name];
        }
        return null;
    }

    public function __isset($name)
    {
        return isset($this->post[$name]) || isset($this->get[$name]);
    }

    public function getRequestType()
    {
        return $this->request_type;
    }

    public function getData()
    {
        if ($this->request_type === 'GET') {
            return $this->get;
        }elseif ($this->request_type === 'POST') {
            return $this->post;
        }
        return null;
    }

    public function setVariable($name, $vali)
    {

    }
}