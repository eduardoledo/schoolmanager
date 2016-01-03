<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $facebookId;

    /**
     * @var User
     *
     * @ORM\OneToMany(targetEntity="School", mappedBy="owner")
     */
    private $mySchools;

    /**
     * @var User
     *
     * @ORM\ManyToMany(targetEntity="School", mappedBy="administrators")
     */
    private $administeredSchools;

    public function __construct()
    {
        $this->administeredSchools = new ArrayCollection();
        parent::__construct();
        // your own logic
    }

    /**
     * 
     * @param string $facebookId
     * @return \AppBundle\Entity\User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Add mySchool
     *
     * @param \AppBundle\Entity\School $mySchool
     *
     * @return User
     */
    public function addMySchool(\AppBundle\Entity\School $mySchool)
    {
        $this->mySchools[] = $mySchool;

        return $this;
    }

    /**
     * Remove mySchool
     *
     * @param \AppBundle\Entity\School $mySchool
     */
    public function removeMySchool(\AppBundle\Entity\School $mySchool)
    {
        $this->mySchools->removeElement($mySchool);
    }

    /**
     * Get mySchools
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMySchools()
    {
        return $this->mySchools;
    }

    /**
     * Add administeredSchool
     *
     * @param \AppBundle\Entity\School $administeredSchool
     *
     * @return User
     */
    public function addAdministeredSchool(\AppBundle\Entity\School $administeredSchool)
    {
        $this->administeredSchools[] = $administeredSchool;

        return $this;
    }

    /**
     * Remove administeredSchool
     *
     * @param \AppBundle\Entity\School $administeredSchool
     */
    public function removeAdministeredSchool(\AppBundle\Entity\School $administeredSchool)
    {
        $this->administeredSchools->removeElement($administeredSchool);
    }

    /**
     * Get administeredSchools
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdministeredSchools()
    {
        return $this->administeredSchools;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    public function __toString()
    {
        $name = trim($this->getFirstName() . " " . $this->getLastName());

        return (strlen($name) > 0) ? $name : parent::__toString();
    }

}
