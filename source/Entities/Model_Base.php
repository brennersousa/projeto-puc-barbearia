<?php

namespace App\Entities;

use App\Support\EntityManager;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\ORM\EntityManager as Manager;

class Model_Base
{
    private $error;

    public function save($showError = false)
    {
        try{
            $this->getEntityManager()->persist($this);
            $this->getEntityManager()->flush();
        }catch(PDOException $e){
            if($showError){
                return $e->getMessage();
            }
            $this->setError("Opss, um erro inesperado aconteceu. Entre em contato com o administrador ou tente novamente mais tarde");
            return null;
        }
        return $this->getId();
    }

    public function remove($showError = false)
    {
        try{
            $this->getEntityManager()->remove($this);
            $this->getEntityManager()->flush();
        }catch(PDOException $e){
            if($showError){
                return $e->getMessage();
            }
            $this->setError("Opss, um erro inesperado aconteceu. Entre em contato com o administrador ou tente novamente mais tarde");
            return null;
        }
        return true;
    }

    public function getError()
    {
        $message =  $this->error;
        $this->setError(null);
        return $message;
    }

    public function setError($message)
    {
        $this->error = $message;
    }

    public function fetchById($id)
    {
        return $this->getEntityManager()->getRepository(get_class($this))->findOneBy(['id' => $id]);
    }

    public function getEntityManager(): Manager
    {
        return EntityManager::getManager();
    }

    public function fetchAll()
    {
        return $this->getEntityManager()->getRepository(get_class($this))->findAll();
    }
}