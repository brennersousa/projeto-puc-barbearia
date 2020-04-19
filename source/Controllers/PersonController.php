<?php

namespace App\Controllers;

use App\Core\Session;
use App\Models\Person;
use App\Models\Role;

class PersonController extends ControllerBase
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function login()
    {
        $email     = filter_var($this->getParam('email'), FILTER_VALIDATE_EMAIL);
        $password  = filter_var($this->getParam('password'), FILTER_DEFAULT);

        if($this->session->has('person')){
            $json = ['success' => false, "message" => "Você já está logado"];
            echo json_encode($json);
            return;
        }
        
        $personWk = new Person();
        $person   = $personWk->fetchByEmail($email);

        // validate user
        if(!$person){
            $json = ['success' => false, "message" => "Usuário ou senha informado está incorreto"];
            echo json_encode($json);
            return;
        }

        // validate password
        if(!passwd_verify($password, $person->getPassword())){
            $json = ['success' => false, "message" => "Usuário ou senha informado está incorreto"];
            echo json_encode($json);
            return;
        }

        $loggedUserRole = $person->getLoggedUserRole();

        if(!$loggedUserRole){
            $json = ['success' => false, "message" => "Usuário não possui autorização para realizar login"];
            echo json_encode($json);
            return;
        }

        switch($loggedUserRole){
            case Role::ROLE_CLIENT:
                $id = $person->getClientObject()->getId();
                $this->session->set('clientId', $id);
                break;
            case Role::ROLE_RECEPTIONIST:
                $id = $person->getReceptionistObject()->getId();
                $this->session->set('receptionistId', $id);
                break;
            case Role::ROLE_ADMINISTRATOR:
                    $id = $person->getAdministratorObject()->getId();
                    $this->session->set('administratorId', $id);
                    break;
        }

        $this->session->set('person', $person);
        $this->session->set('loggedUserRole', $loggedUserRole);

        $user[] = [
            'firstName' => $person->getFirstName(),
            'lastName'  => $person->getLastName(),
            'email'     => $person->getEmail(),
            'photo'     => $person->getPhoto(),
            'loggedUserRole' => $loggedUserRole
        ];

        $json = ['success' => true, "message" => "Usuário logou com sucesso", 'user' => $user];
        echo json_encode($json);
    }

    public function logout()
    {
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não está logado para poder realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $this->session->destroy();
        $json = ['success' => true, "message" => "Você deslogou com sucesso"];
        echo json_encode($json);
    }

    // public function
}