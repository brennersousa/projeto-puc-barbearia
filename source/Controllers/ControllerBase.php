<?php

namespace App\Controllers;

use App\Core\Session;
use CoffeeCode\Router\Router;

class ControllerBase
{
    private $data;

    /** @var Session */
    protected $session;

    public function __construct(Router $router)
    {
        $this->data = $router->data();
        $this->session = new Session();
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