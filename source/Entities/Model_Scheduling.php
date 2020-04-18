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
class Model_Scheduling extends Model_Base
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="App\Models\Barber")
     * @JoinColumn(name="barber_id", referencedColumnName="id", nullable=false)
     */
    private $barber;

    /**
     * @ManyToOne(targetEntity="App\Models\Client")
     * @JoinColumn(name="client_id", referencedColumnName="id", nullable=false)
     */
    private $client;

    /**
     * @ManyToOne(targetEntity="App\Models\Administrator")
     * @JoinColumn(name="administrator_id", referencedColumnName="id", nullable=true)
     */
    private $administrator;

    /**
     * @ManyToOne(targetEntity="App\Models\Receptionist")
     * @JoinColumn(name="receptionist_id", referencedColumnName="id", nullable=true)
     */
    private $receptionist;

    /** @Column(type="datetime", name="date") */
    private $date;

     /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="App\Models\Service")
     * @JoinTable(name="ServiceScheduling",
     *      joinColumns={@JoinColumn(name="scheduling_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="service_id", referencedColumnName="id")}
     *      )
     */
    private $services;

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
     * Get the value of barber
     */ 
    public function getBarber()
    {
        return $this->barber;
    }

    /**
     * Get the value of client
     */ 
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the value of administrator
     */ 
    public function getAdministrator()
    {
        return $this->administrator;
    }

    /**
     * Get the value of receptionist
     */ 
    public function getReceptionist()
    {
        return $this->receptionist;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get many Users have Many Groups.
     */ 
    public function getServices()
    {
        return $this->services;
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
     * Set the value of barber
     */ 
    public function setBarber($barber)
    {
        $this->barber = $barber;
    }

    /**
     * Set the value of client
     */ 
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * Set the value of administrator
     */ 
    public function setAdministrator($administrator)
    {
        $this->administrator = $administrator;
    }

    /**
     * Set the value of receptionist
     */ 
    public function setReceptionist($receptionist)
    {
        $this->receptionist = $receptionist;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Set many Users have Many Groups.
     */ 
    public function setServices($services)
    {
        $this->services = $services;
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