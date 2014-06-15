<?php

namespace Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\Role;
/**
 * User
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var string
     */
    private $first_name;

    /**
     * @var string
     */
    private $last_name;

    /**
     * @var \DateTime
     */
    private $created;

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';


    public function __construct() {
        $this->setUserRoles(array(static::ROLE_USER));
    }
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
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles->map(function($role) {
            return new Role($role);
        })->toArray();
    }

    public function getUserRoles() {
        return $this->roles->toArray();
    }

    /**
     * @param array $roles
     * @return $this
    */
    public function setUserRoles( array $roles ) {
        $this->roles = new ArrayCollection();
        foreach( $roles as $key => $role )
        {
            $this->roles->add( strtoupper( (string)$role ) );
        }

        return $this;
    }


    /**
     * Set first_name
     *
     * @param string $firstName
     * @return User
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
     * Set last_name
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;
    
        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return User
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
     * @ORM\PrePersist
     */
    public function eraseCredentials()
    {
        // Add your code here
    }

    public function getUsername() {
        return $this->getEmail();
    }


    /**
     * @return array
     */
    public static function getAvailableRoles()
    {
        $ref = new \ReflectionClass( get_called_class() );
        $roles = array();

        foreach( $ref->getConstants() as $name => $val )
        {
            if( 'ROLE_' !== substr( $name , 0 , 5 ) )
            {
                continue;
            }

            $roles[$val] = $val;
        }

        return $roles;
    }

}
