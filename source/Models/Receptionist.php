<?php

namespace App\Models;

use App\Entities\Model_Receptionist;

/** 
 * @Table(name="Receptionists")
 * @HasLifecycleCallbacks
 * @Entity 
 */
class Receptionist extends Model_Receptionist
{
    public function remove($showError = false)
    {
        if($this->hasScheduling())
        {
            $this->setError("O usuário não pode ser removido, pois o mesmo já realizou agendamentos no sistema");
            return false; 
        }
        $person = $this->getPerson();
        $result = parent::remove($showError);

        if($result){
            $person->removeRole(Role::ROLE_RECEPTIONIST);
            $person->remove();
        }

        return $result;
    }

    public function hasScheduling()
    {
        return $this->getEntityManager()->getRepository(Scheduling::class)->findOneBy(['receptionist' => $this->getId()]);
    } 
}