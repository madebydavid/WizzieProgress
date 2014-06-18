<?php

namespace Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 */
class Student
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $first_name;

    /**
     * @var integer
     */
    private $age;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \Model\User
     */
    private $created_by;

    /**
     * @var \Model\Location
     */
    private $location_id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Student
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;
    
        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set age
     *
     * @param integer $age
     * @return Student
     */
    public function setAge($age)
    {
        $this->age = $age;
    
        return $this;
    }

    /**
     * Get age
     *
     * @return integer 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Student
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created_by
     *
     * @param \Model\User $createdBy
     * @return Student
     */
    public function setCreatedBy(\Model\User $createdBy = null)
    {
        $this->created_by = $createdBy;
    
        return $this;
    }

    /**
     * Get created_by
     *
     * @return \Model\User 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set location_id
     *
     * @param \Model\Location $locationId
     * @return Student
     */
    public function setLocationId(\Model\Location $locationId = null)
    {
        $this->location_id = $locationId;
    
        return $this;
    }

    /**
     * Get location_id
     *
     * @return \Model\Location 
     */
    public function getLocationId()
    {
        return $this->location_id;
    }
}