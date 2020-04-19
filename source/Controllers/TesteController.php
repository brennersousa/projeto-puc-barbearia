<?php

namespace App\Controllers;

class TesteController extends ControllerBase
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function home(): string
    {
        // $json = 
        exit(json_encode(['sucess' => true]));
  
    }

    public function error()
    {
        $json = ['message' => "Erro inesperado, verifique se a rota informada existe", "error" => $this->getParam('error')];
        echo json_encode($json);
    }
}