<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/12/2019
 * Time: 19:33
 */

namespace App\Entities;

use App\Models\Person;
use App\Models\Role;
use DateTime;

/** @MappedSuperclass */
class Model_Barber extends Model_Base
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="App\Models\Person")
     * @JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     */
    private $person;

    /** @Column(type="integer", name="status") */
    private $status;

    /** @Column(type="datetime", name="created_at") */
    private $createdAt;

    /** @Column(type="datetime", name="update_at", nullable=true) */
    private $updateAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updateAt
     */ 
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $person
     */
    public function setPerson(Person $person): void
    {
        if(!$this->getId())
        {
            $role = new Role();
            $role->setPerson($person);
            $role->setRole(Role::ROLE_BARBER);
            $role->save($role);
        }

        $this->person = $person;
    }

    /**
     * Set the value of status
     */ 
    public function setStatus($status)
    {
        if($this->getId())
        {
            if($status == Role::STATUS_ACTIVE){
                if(!$this->getPerson()->isBarber()){
                    $role = new Role();
                    $role->setPerson($this->getPerson());
                    $role->setRole(Role::ROLE_BARBER);
                    $role->save($role);
                }
            }elseif($status == Role::STATUS_INACTIVE){
                if($this->getPerson()->isBarber()){
                   $this->getPerson()->removeRole(Role::ROLE_BARBER);
                }
            }
        }

        $this->status = $status;
    }

    /**
     * Set the value of createdAt
     * @PrePersist
     */ 
    public function preCreatedAt()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * Set the value of updateAt
     * @PreUpdate
     */ 
    public function preUpdateAt()
    {
        $this->updateAt = new DateTime();
    }
}