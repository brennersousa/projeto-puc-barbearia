<?php
namespace App\Entities;

use DateTime;

/** @MappedSuperclass */
class Model_Receptionist extends Model_Base
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="App\Models\Person", inversedBy="roles")
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
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of person
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
     * Set the value of person
     */ 
    public function setPerson($person)
    {
        $this->person = $person;
    }

    /**
     * Set the value of status
     */ 
    public function setStatus($status)
    {
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