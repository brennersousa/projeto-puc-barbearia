<?php
namespace App\Models;

use App\Entities\Model_Barber;

/**
 * @Table(name="Barbers")
 * @HasLifecycleCallbacks
 * @Entity 
 */
class Barber extends Model_Barber
{
    public function hasScheduling()
    {
        return $this->getEntityManager()->getRepository(Scheduling::class)->findOneBy(['barber' => $this->getId()]);
    } 

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
            $person->removeRole(Role::ROLE_BARBER);
            $person->remove();
        }

        return $result;
    }
}