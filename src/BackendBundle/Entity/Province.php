<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Province
 *
 * @ORM\Table(name="province")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\ProvinceRepository")
 */
class Province
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
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Country", inversedBy="provinces", cascade={"persist"})
     *  @ORM\JoinColumn(name="country_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $countryId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\City", mappedBy="province_id" ,cascade={"persist"},orphanRemoval=true)
     */
    private $cities;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Province
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
     * Remove cities
     *
     * @param \BackendBundle\Entity\City $cities
     */
    public function removeCities(\BackendBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Get cities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCities()
    {
        return $this->cities;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cities = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Add cities
     *
     * @param \BackendBundle\Entity\City $cities
     * @return Province
     */
    public function addCity(\BackendBundle\Entity\City $cities)
    {
        $this->cities[] = $cities;

        return $this;
    }

    /**
     * Remove cities
     *
     * @param \BackendBundle\Entity\City $cities
     */
    public function removeCity(\BackendBundle\Entity\City $cities)
    {
        $this->cities->removeElement($cities);
    }

    /**
     * Set countryId
     *
     * @param \BackendBundle\Entity\Country $countryId
     * @return Province
     */
    public function setCountryId(\BackendBundle\Entity\Country $countryId = null)
    {
        $this->countryId = $countryId;

        return $this;
    }

    /**
     * Get countryId
     *
     * @return \BackendBundle\Entity\Country 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }
}
