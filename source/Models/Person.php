<?php
namespace App\Models;

use App\Entities\Model_Person;
use App\Support\EntityManager;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Table(name="Persons")
 * @HasLifecycleCallbacks
 * @Entity 
 */
class Person extends Model_Person
{
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function fetchByEmail($email)
    {
        return $this->getEntityManager()->getRepository(Person::class)->findOneBy(['email' => $email]);
    }

    /**
     * @return boolean
     */
    public function isClient()
    {
      return $this->hasRole(Role::ROLE_CLIENT);
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
      return $this->hasRole(Role::ROLE_ADMINISTRATOR);
    }

    /**
     * @return boolean
     */
    public function isReceptionist()
    {
      return $this->hasRole(Role::ROLE_RECEPTIONIST);
    }

    /**
     * @param int $role
     * @return boolean
     */
    public function hasRole($role): bool
    {
        $roles = $this->getRoles();
        if(count($roles) > 0){
            foreach($roles as $objectRole){
                if($objectRole->getRole() == $role){
                    return true;
                }
            }
        }
        return false;
    }

    public function getLoggedUserRole()
    {
        if($this->isAdmin()){
            return Role::ROLE_ADMINISTRATOR;
        }

        if($this->isReceptionist()){
            return Role::ROLE_RECEPTIONIST;
        }

        if($this->isClient()){
            return Role::ROLE_CLIENT;
        }

        return null;
    }

    public function getClientObject()
    {
        return $this->getClientByPersonId($this->getId());
    }

    public function getClientByPersonId($id)
    {
        return $this->getEntityManager()->getRepository(Client::class)->findOneBy(['person' => $id]);
    }

    public function getReceptionistObject()
    {
        return $this->getReceptionistByPersonId($this->getId());
    }

    public function getReceptionistByPersonId($id)
    {
        return $this->getEntityManager()->getRepository(Receptionist::class)->findOneBy(['person' => $id]);
    }

    public function getAdministratorObject()
    {
        return $this->getAdministratorByPersonId($this->getId());
    }

    public function getAdministratorByPersonId($id)
    {
        return $this->getEntityManager()->getRepository(Administrator::class)->findOneBy(['person' => $id]);
    }

    public function save($showError = false)
    {
        if(!$this->getFirstName())
        {
            $this->setError("O nome do usuário não foi informado");
            return false;
        }
        
        if(!$this->getLastName())
        {
            $this->setError("O sobre nome do usuário não foi informado");
            return false;   
        }

        if(!$this->getEmail())
        {
            $this->setError("O email informado não é válido");
            return false; 
        }

        if (!is_passwd($this->getPassword()))
        {
            $min = CONF_PASSWD_MIN_LEN;
            $max = CONF_PASSWD_MAX_LEN;
            $this->setError("A senha deve ter entre {$min} e {$max} caracteres");
            return false;
        } else {
            $this->setPassword(passwd($this->getPassword()));
        }

        $person = $this->fetchByEmail($this->getEmail());
        if($person){
            if(($person->getId() != $this->getId())){
                $this->setError("Já existe uma pessoa cadastrada com o e-mail informado");
                return false; 
            }
        }

        return parent::save($showError);   
    }


    public function remove($showError = false)
    {
        if(count($this->getRoles()) > 0)
        {
            $this->setError("Está pessoa não pode ser removida pois possui funções registradas no sistema");
            return false; 
        }

        return parent::remove($showError);
    }

    public function removeRole($role)
    {
        $roles = $this->getRoles();

        if(count($roles) > 0){
            foreach($roles as $objectRole){
                if($objectRole->getRole() == $role){
                    $objectRole->remove();
                }
            }
        }
    }
}