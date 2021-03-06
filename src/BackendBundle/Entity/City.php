<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\CityRepository")
 */
class City
{
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
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Province
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Province", inversedBy="cities", cascade={"persist"})
     *  @ORM\JoinColumn(name="province_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $provinceId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Institution", mappedBy="cityId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $institution;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="cityId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $users;

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
     * Set name
     *
     * @param string $name
     * @return City
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
     * Constructor
     */
    public function __construct()
    {
        $this->institution = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getName();
    }


    /**
     * Set provinceId
     *
     * @param \BackendBundle\Entity\Province $provinceId
     * @return City
     */
    public function setProvinceId(\BackendBundle\Entity\Province $provinceId = null)
    {
        $this->provinceId = $provinceId;

        return $this;
    }

    /**
     * Get provinceId
     *
     * @return \BackendBundle\Entity\Province 
     */
    public function getProvinceId()
    {
        return $this->provinceId;
    }

    /**
     * Add institution
     *
     * @param \BackendBundle\Entity\Institution $institution
     * @return City
     */
    public function addInstitution(\BackendBundle\Entity\Institution $institution)
    {
        $this->institution[] = $institution;

        return $this;
    }

    /**
     * Remove institution
     *
     * @param \BackendBundle\Entity\Institution $institution
     */
    public function removeInstitution(\BackendBundle\Entity\Institution $institution)
    {
        $this->institution->removeElement($institution);
    }

    /**
     * Get institution
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return City
     */
    public function addUser(\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UserBundle\Entity\User $users
     */
    public function removeUser(\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
