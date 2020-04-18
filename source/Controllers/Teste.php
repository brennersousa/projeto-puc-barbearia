<?php

namespace App\Controllers;

class Teste
{
    public function __construct($router)
    {
        $this->router = $router;
    }

    public function home(): string
    {
        // $json = 
        exit(json_encode(['sucess' => true]));
  
    }

    public function redirect(): void
    {
        $this->router->redirect("name.hello");
    }
}