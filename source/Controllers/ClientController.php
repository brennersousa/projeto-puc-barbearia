<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\Person;
use App\Models\Role;
use CoffeeCode\Router\Router;
use App\Support\Upload;

class ClientController extends ControllerBase
{
    public function __construct(Router $router)
    {
        parent::__construct($router);
    }

    public function register(): void
    {
        $firstName = filter_var($this->getParam('firstName'), FILTER_DEFAULT);
        $lastName  = filter_var($this->getParam('lastName'), FILTER_DEFAULT);
        $email     = filter_var($this->getParam('email'), FILTER_VALIDATE_EMAIL);
        $password  = filter_var($this->getParam('password'), FILTER_DEFAULT);

        $person = new Person();
        
        $result = $person->fetchByEmail($email);
        
        if(!$result)
        {
            $person->setFirstName($firstName);
            $person->setLastName($lastName);
            $person->setEmail($email);
            $person->setPassword($password);
            if(!$person->save())
            {
                $json = ['success' => false, 'message' => $person->getError()];
                echo json_encode($json);
                return;
            }
        }else{
            $person = $result;
            if($person->isClient()){
                $json = ['success' => false, 'message' => "Já existe um cliente cadastrado com esse e-mail"];
                echo json_encode($json);
                return;
            }
        }

        $client = new Client();
        $client->setPerson($person);
        
        if(!$client->save())
        {
            $json = ['success' => false, 'message' => "Opss, algum erro aconteceu. Tente novamente mais tarde"];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "Cadastro realizado com sucesso", 'id' => $client->getId()];
        echo json_encode($json);
    }

    public function update()
    { 
        $id        = filter_var($this->getParam('id'), FILTER_VALIDATE_INT);
        $firstName = filter_var($this->getParam('firstName'), FILTER_DEFAULT);
        $lastName  = filter_var($this->getParam('lastName'), FILTER_DEFAULT);
        $email     = filter_var($this->getParam('email'), FILTER_VALIDATE_EMAIL);
        $password  = filter_var($this->getParam('password'), FILTER_DEFAULT);
        $photo     = $this->getFile('photo');

        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole == Role::ROLE_CLIENT && $this->session->clientId != $id){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $client = new Client();

        $client = $client->fetchById($id);

        if(!$client)
        {
            $json = ['success' => false, "message" => "Cliente informado é inválido"];
            echo json_encode($json);
            return;
        }

        $person = $client->getPerson();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $person->setEmail($email);
        $person->setPassword($password);
        
        if(is_array($photo)){
            $upload = new Upload();
            $photoName = 'profile-' . $person->getId(). time();
            $imagePath = $upload->image($photo, $photoName, 500);
            if(!$imagePath){
                $json = ['success' => false, 'message' => $upload->message()];
                echo json_encode($json);
                return;
            }
            $oldImagePath = $person->getPhoto();
            $person->setPhoto($imagePath);
        }

        if(!$person->save())
        {
            if(!empty($imagePath)){
                $upload->remove($imagePath);
            }

            $json = ['success' => false, 'message' => $person->getError()];
            echo json_encode($json);
            return;
        }

        if(!empty($oldImagePath)){
            $upload->remove($oldImagePath);
        }
        
        $json = ['success' => true, "message" => "cliente atualizado com sucesso"];
        echo json_encode($json);
    }
    
    public function getAllClients()
    {
        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole == Role::ROLE_CLIENT){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $clientWk = new Client();
        $result = $clientWk->fetchAll();

        $clients = [];
        if(count($result) > 0){
            foreach($result as $client){
                $photo = ($client->getPerson()->getPhoto() ? url($client->getPerson()->getPhoto()) : null);
                $clients[] = [
                    'firstName' => $client->getPerson()->getFirstName(),
                    'lastName'  => $client->getPerson()->getLastName(),
                    'email'     => $client->getPerson()->getEmail(),
                    'photo'     => $photo,
                    'id'        => $client->getId()
                ];
            }
        }

        echo json_encode($clients);
    }

    public function remove($request)
    {
        $id = $request['id'] ?? null;

        $client = new Client();

        $client = $client->fetchById($id);

        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole == Role::ROLE_CLIENT){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }
   
        if(!$client)
        {
           $json = ['success' => false, "message" => "Cliente informado é inválido"];
           echo json_encode($json);
           return;
        }

        if(!$client->remove()){
            $json = ['success' => false, "message" => $client->getError()];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "o cliente foi removido do sistema"];
        echo json_encode($json);
    }
}