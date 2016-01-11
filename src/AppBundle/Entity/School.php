<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * School
 *
 * @ORM\Table(name="school")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SchoolRepository")
 * @UniqueEntity(
 *      fields={"legalName", "owner"}
 * )
 */
class School
{

    use \AppBundle\Traits\TimestampableEntityTrait;

    const STATUS_PENDING = 'status_pending';
    const STATUS_ENABLED = 'status_enabled';
    const STATUS_DISABLED = 'status_disabled';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $legalName;

    /**
     *
     * @ORM\Column(type="string", length=50)
     */
    private $status = School::STATUS_PENDING;

    /**
     *
     * @var string
     * @Gedmo\Slug(fields={"name", "legalName"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var User
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("\AppBundle\Entity\User")
     * @ORM\ManyToOne(targetEntity="User", inversedBy="mySchools")
     */
    private $owner;

    /**
     * @var \Doctrine\Common\Collections\Collection|User[]
     * @ORM\ManyToMany(targetEntity="User", inversedBy="administeredSchools")
     */
    private $administrators;
    /**
     * @var User
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="school")
     */
    private $courses;

    public function __construct()
    {
        $this->administrators = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return School
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set legalName
     *
     * @param string $legalName
     *
     * @return School
     */
    public function setLegalName($legalName)
    {
        $this->legalName = $legalName;

        return $this;
    }

    /**
     * Get legalName
     *
     * @return string
     */
    public function getLegalName()
    {
        return $this->legalName;
    }

    /**
     * Add administrator
     *
     * @param \AppBundle\Entity\User $administrator
     *
     * @return School
     */
    public function addAdministrator(\AppBundle\Entity\User $administrator)
    {
        $this->administrators[] = $administrator;

        return $this;
    }

    /**
     * Remove administrator
     *
     * @param \AppBundle\Entity\User $administrator
     */
    public function removeAdministrator(\AppBundle\Entity\User $administrator)
    {
        $this->administrators->removeElement($administrator);
    }

    /**
     * Get administrators
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdministrators()
    {
        return $this->administrators;
    }

    /**
     * Set owner
     *
     * @param \AppBundle\Entity\User $owner
     *
     * @return School
     */
    public function setOwner(\AppBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \AppBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return School
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function __toString()
    {
        return $this->getName() . " | " . $this->getLegalName() . " | " . $this->getOwner();
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return School
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

}
