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
class Model_Client extends Model_Base
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
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
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
     * @param int $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person): void
    {
        if(!$this->getId())
        {
            $role = new Role();
            $role->setPerson($person);
            $role->setRole(Role::ROLE_CLIENT);
            $role->save($role);
            $person->getRoles()->add($role);
        }

        $this->person = $person;
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