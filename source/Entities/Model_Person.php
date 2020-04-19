<?php
namespace App\Entities;

use App\Support\Upload;
use DateTime;

/** @MappedSuperclass */
class Model_Person extends Model_Base
{
    /**
     * @Id
     * @Column(type="integer", name="id")
     * @GeneratedValue
     */
    private $id;

    /** @Column(type="string", name="first_name", length=65) */
    private $firstName;

    /** @Column(type="string", name="last_name", length=65) */
    private $lastName;

    /** @Column(type="string", name="email", length=65) */
    private $email;

    /** @Column(type="string", name="photo", nullable=true) */
    private $photo;

    /** @OneToMany(targetEntity="App\Models\Role", mappedBy="person") */
    protected $roles;

    /** @Column(type="string", name="password", nullable=true) */
    private $password;

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
    public function getLastName()
    {
        return $this->lastName;
    }
    
    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get the value of photo
     */ 
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Get the value of roles
     */ 
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get the value of created_at
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of update_at
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
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param mixed $lasteName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }
    

    /**
     * Set the value of email
     */ 
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set the value of photo
     */ 
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * Set the value of password
     */ 
    public function setPassword($password)
    {
        $this->password = $password;
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