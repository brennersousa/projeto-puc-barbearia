<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/12/2019
 * Time: 19:33
 */

namespace App\Entities;

use App\Models\Person;

/** @MappedSuperclass */
class Model_Role extends Model_Base
{
    const ROLE_CLIENT        = 1;
    const ROLE_BARBER        = 2;
    const ROLE_RECEPTIONIST  = 3;
    const ROLE_ADMINISTRATOR = 4;

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

    /**  @Column(type="integer", name="role") */
    private $role;

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
    public function getPerson(): Person
    {
        return $this->person;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
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
        $this->person = $person;
    }


    /**
     * Set the value of role
     */ 
    public function setRole($role)
    {
        $this->role = $role;
    }
}