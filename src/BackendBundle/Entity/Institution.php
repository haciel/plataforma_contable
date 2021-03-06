<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Institution
 *
 * @ORM\Table(name="institution")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\InstitutionRepository")
 */
class Institution
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
     * @var int
     *
     * @ORM\Column(name="number_estudent", type="integer")
     */
    private $numberEstudent;

    /**
     * @var city
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\City", inversedBy="institution", cascade={"persist"})
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $cityId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Company", mappedBy="institutionId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $companies;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="institutionId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $users;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enable;


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
     * @return Institution
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
     * Remove companies
     *
     * @param \BackendBundle\Entity\Company $companies
     */
    public function removeCompanies(\BackendBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }

    /**
     * Get companies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * Set enable
     *
     * @param boolean $enable
     * @return Institution
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * Get enable
     *
     * @return boolean 
     */
    public function getEnable()
    {
        return $this->enable;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->companies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->name;
    }

    /**
     * Set numberEstudent
     *
     * @param integer $numberEstudent
     * @return Institution
     */
    public function setNumberEstudent($numberEstudent)
    {
        $this->numberEstudent = $numberEstudent;

        return $this;
    }

    /**
     * Get numberEstudent
     *
     * @return integer 
     */
    public function getNumberEstudent()
    {
        return $this->numberEstudent;
    }

    /**
     * Set city_id
     *
     * @param \BackendBundle\Entity\City $cityId
     * @return Institution
     */
    public function setCityId(\BackendBundle\Entity\City $cityId = null)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get city_id
     *
     * @return \BackendBundle\Entity\City 
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Add companies
     *
     * @param \BackendBundle\Entity\Company $companies
     * @return Institution
     */
    public function addCompany(\BackendBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;

        return $this;
    }

    /**
     * Remove companies
     *
     * @param \BackendBundle\Entity\Company $companies
     */
    public function removeCompany(\BackendBundle\Entity\Company $companies)
    {
        $this->companies->removeElement($companies);
    }


    /**
     * Add users
     *
     * @param \UserBundle\Entity\User $users
     * @return Institution
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
