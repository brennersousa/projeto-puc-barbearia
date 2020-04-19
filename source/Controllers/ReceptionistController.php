<?php

namespace App\Controllers;

use App\Models\Client;
use App\Models\Person;
use App\Models\Receptionist;
use App\Models\Role;
use CoffeeCode\Router\Router;
use App\Support\Upload;

class ReceptionistController extends ControllerBase
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

        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole != Role::ROLE_ADMINISTRATOR){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

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
            if($person->isReceptionist()){
                $json = ['success' => false, 'message' => "Já existe um recepcionista registrado com esse e-mail"];
                echo json_encode($json);
                return;
            }
        }

        $receptionist = new Receptionist();
        $receptionist->setPerson($person);
        $receptionist->setStatus(Role::STATUS_ACTIVE);
        
        if(!$receptionist->save())
        {
            $json = ['success' => false, 'message' => "Opss, algum erro aconteceu. Tente novamente mais tarde"];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "Cadastro realizado com sucesso", 'id' => $receptionist->getId()];
        echo json_encode($json);
    }

       
    public function getAllReceptionist()
    {
        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole != Role::ROLE_ADMINISTRATOR){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $receptionistWk = new Receptionist();
        $result = $receptionistWk->fetchAll();

        $receptionists = [];
        if(count($result) > 0){
            foreach($result as $receptionist){
                $receptionists[] = [
                    'firstName' => $receptionist->getPerson()->getFirstName(),
                    'lastName'  => $receptionist->getPerson()->getLastName(),
                    'email'     => $receptionist->getPerson()->getEmail(),
                    'photo'     => $receptionist->getPerson()->getPhoto(),
                    'status'    => $receptionist->getStatus(),
                    'id'        => $receptionist->getId()
                ];
            }
        }

        echo json_encode($receptionists);
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
        if(!$this->session->has('person') || $this->session->loggedUserRole == Role::ROLE_CLIENT){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole == Role::ROLE_RECEPTIONIST && $this->session->receptionistId != $id){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $receptionist = new Receptionist();

        $receptionist = $receptionist->fetchById($id);
      
        if(!$receptionist)
        {
            $json = ['success' => false, "message" => "usuário informado é inválido"];
            echo json_encode($json);
            return;
        }

        $person = $receptionist->getPerson();
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
        
        $json = ['success' => true, "message" => "usuário atualizado com sucesso"];
        echo json_encode($json);
    }

    public function remove($request)
    {
        
        $id =  $id = $request['id'] ?? null;
        
        $receptionist = new Receptionist();

        $receptionist = $receptionist->fetchById($id);
        
        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole != Role::ROLE_ADMINISTRATOR){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }
   
        if(!$receptionist)
        {
           $json = ['success' => false, "message" => "Usuário informado é inválido"];
           echo json_encode($json);
           return;
        }

        if($receptionist->hasScheduling()){
           $receptionist->setStatus(Role::STATUS_INACTIVE);

            if(!$receptionist->save()){
                $json = ['success' => false, "message" => $receptionist->getError()];
                echo json_encode($json);
                return;
            }

            $json = ['success' => false, "message" => "Usuário desativado do sistema"];
            echo json_encode($json);
            return;
        }
      
        if(!$receptionist->remove()){
            $json = ['success' => false, "message" => $receptionist->getError()];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "Usuário foi removido do sistema"];
        echo json_encode($json);
    }
}