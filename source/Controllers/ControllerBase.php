<?php

namespace App\Controllers;

use CoffeeCode\Router\Router;

class ControllerBase
{
    private $data;

    public function __construct(Router $router)
    {
        $this->data = $router->data();
    }

    public function getParam($name)
    {
        return ($this->data[$name] ?? null);
    }

    public function getFile($name)
    {
        return ($_FILES[$name] ?? null);
    }

}