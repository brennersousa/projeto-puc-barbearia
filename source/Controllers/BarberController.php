<?php

namespace App\Controllers;

use App\Models\Barber;
use App\Models\Client;
use App\Models\Person;
use App\Models\Receptionist;
use App\Models\Role;
use CoffeeCode\Router\Router;
use App\Support\Upload;

class BarberController extends ControllerBase
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
            if($person->isBarber()){
                $json = ['success' => false, 'message' => "Já existe um barbeiro registrado com esse e-mail"];
                echo json_encode($json);
                return;
            }
        }

        $barber = new Barber();
        $barber->setPerson($person);
        $barber->setStatus(Role::STATUS_ACTIVE);
        
        if(!$barber->save())
        {
            $json = ['success' => false, 'message' => "Opss, algum erro aconteceu. Tente novamente mais tarde"];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "Cadastro realizado com sucesso", 'id' => $barber->getId()];
        echo json_encode($json);
    }

       
    public function getAllBarbers()
    {
        // user is not logged in
        if(!$this->session->has('person')){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        // user not autorization
        if($this->session->loggedUserRole != Role::ROLE_ADMINISTRATOR && $this->session->loggedUserRole != Role::ROLE_RECEPTIONIST){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $barberWk = new Barber();
        $result = $barberWk->fetchAll();

        $barbers = [];
        if(count($result) > 0){
            foreach($result as $barber){
                $photo = ($barber->getPerson()->getPhoto() ? url($barber->getPerson()->getPhoto()) : null);
                $barbers[] = [
                    'firstName' => $barber->getPerson()->getFirstName(),
                    'lastName'  => $barber->getPerson()->getLastName(),
                    'email'     => $barber->getPerson()->getEmail(),
                    'photo'     => $photo,
                    'status'    => $barber->getStatus(),
                    'id'        => $barber->getId()
                ];
            }
        }
     
        echo json_encode($barbers);
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
        if($this->session->loggedUserRole != Role::ROLE_ADMINISTRATOR){
            $json = ['success' => false, "message" => "Você não possui autorização para realizar essa ação"];
            echo json_encode($json);
            return;
        }

        $barber = new Barber();

        $barber = $barber->fetchById($id);
      
        if(!$barber)
        {
            $json = ['success' => false, "message" => "usuário informado é inválido"];
            echo json_encode($json);
            return;
        }

        $person = $barber->getPerson();
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

        $barber = new Barber();

        $barber = $barber->fetchById($id);
        
   
        if(!$barber)
        {
           $json = ['success' => false, "message" => "Usuário informado é inválido"];
           echo json_encode($json);
           return;
        }

        if($barber->hasScheduling()){
           $barber->setStatus(Role::STATUS_INACTIVE);

            if(!$barber->save()){
                $json = ['success' => false, "message" => $barber->getError()];
                echo json_encode($json);
                return;
            }

            $json = ['success' => false, "message" => "Usuário desativado do sistema"];
            echo json_encode($json);
            return;
        }
      
        if(!$barber->remove()){
            $json = ['success' => false, "message" => $barber->getError()];
            echo json_encode($json);
            return;
        }

        $json = ['success' => true, "message" => "Usuário foi removido do sistema"];
        echo json_encode($json);
    }
}