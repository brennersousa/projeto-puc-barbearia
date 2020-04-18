<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/12/2019
 * Time: 19:33
 */

namespace App\Entities;

use DateTime;

/** @MappedSuperclass */
class Model_Administrator extends Model_Base
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
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @param mixed $person
     */
    public function setPerson($person): void
    {
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