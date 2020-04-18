<?php

namespace App\Models;

use App\Entities\Model_Client;
use App\Support\EntityManager;

/** 
 * @Table(name="Clients")
 * @HasLifecycleCallbacks
 * @Entity 
 */
class Client extends Model_Client
{
    public function remove($showError = false)
    {
        if($this->hasScheduling())
        {
            $this->setError("O cliente não pode ser removido, pois o mesmo já realizou agendamentos no sistema");
            return false; 
        }
        $person = $this->getPerson();
        $result = parent::remove($showError);

        if($result){
            $person->removeRole(Role::ROLE_CLIENT);
            $person->remove();
        }

        return $result;
    }

    public function hasScheduling()
    {
        return $this->getEntityManager()->getRepository(Scheduling::class)->findOneBy(['client' => $this->getId()]);
    } 
}