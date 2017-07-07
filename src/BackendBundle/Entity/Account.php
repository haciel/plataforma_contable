<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\AccountRepository")
 */
class Account
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
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var AccountType
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\AccountType", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(name="accounttype_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $accounttypeId;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Company", inversedBy="accounts", cascade={"persist"})
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $companyId;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Operations", mappedBy="accountId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $operations;
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
     * Set code
     *
     * @param integer $code
     * @return Account
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Account
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
        $this->operations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set accounttypeId
     *
     * @param \BackendBundle\Entity\AccountType $accounttypeId
     * @return Account
     */
    public function setAccounttypeId(\BackendBundle\Entity\AccountType $accounttypeId = null)
    {
        $this->accounttypeId = $accounttypeId;

        return $this;
    }

    /**
     * Get accounttypeId
     *
     * @return \BackendBundle\Entity\AccountType 
     */
    public function getAccounttypeId()
    {
        return $this->accounttypeId;
    }

    /**
     * Set companyId
     *
     * @param \BackendBundle\Entity\Company $companyId
     * @return Account
     */
    public function setCompanyId(\BackendBundle\Entity\Company $companyId = null)
    {
        $this->companyId = $companyId;

        return $this;
    }

    /**
     * Get companyId
     *
     * @return \BackendBundle\Entity\Company 
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * Add operations
     *
     * @param \BackendBundle\Entity\Operations $operations
     * @return Account
     */
    public function addOperation(\BackendBundle\Entity\Operations $operations)
    {
        $this->operations[] = $operations;

        return $this;
    }

    /**
     * Remove operations
     *
     * @param \BackendBundle\Entity\Operations $operations
     */
    public function removeOperation(\BackendBundle\Entity\Operations $operations)
    {
        $this->operations->removeElement($operations);
    }

    /**
     * Get operations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOperations()
    {
        return $this->operations;
    }
}
