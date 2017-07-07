<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\CompanyRepository")
 */
class Company
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="companies", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $userId;

    /**
     * @var Institution
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Institution", inversedBy="companies", cascade={"persist"})
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $institutionId;

    /**
     * @var EducationalLevel
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\EducationalLevel", inversedBy="companies", cascade={"persist"})
     * @ORM\JoinColumn(name="educationallevel_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $educationallevelId;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\AccountantMove", mappedBy="companyId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $accountansMove;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\Account", mappedBy="companyId" ,cascade={"persist"},orphanRemoval=true)
     */
    private $accounts;

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
     * @return Company
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
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accountansMove = new \Doctrine\Common\Collections\ArrayCollection();
        $this->accounts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set userId
     *
     * @param \UserBundle\Entity\User $userId
     * @return Company
     */
    public function setUserId(\UserBundle\Entity\User $userId = null)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return \UserBundle\Entity\User 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set institutionId
     *
     * @param \BackendBundle\Entity\Institution $institutionId
     * @return Company
     */
    public function setInstitutionId(\BackendBundle\Entity\Institution $institutionId = null)
    {
        $this->institutionId = $institutionId;

        return $this;
    }

    /**
     * Get institutionId
     *
     * @return \BackendBundle\Entity\Institution 
     */
    public function getInstitutionId()
    {
        return $this->institutionId;
    }

    /**
     * Set educationallevelId
     *
     * @param \BackendBundle\Entity\EducationalLevel $educationallevelId
     * @return Company
     */
    public function setEducationallevelId(\BackendBundle\Entity\EducationalLevel $educationallevelId = null)
    {
        $this->educationallevelId = $educationallevelId;

        return $this;
    }

    /**
     * Get educationallevelId
     *
     * @return \BackendBundle\Entity\EducationalLevel 
     */
    public function getEducationallevelId()
    {
        return $this->educationallevelId;
    }

    /**
     * Add accountansMove
     *
     * @param \BackendBundle\Entity\AccountantMove $accountansMove
     * @return Company
     */
    public function addAccountansMove(\BackendBundle\Entity\AccountantMove $accountansMove)
    {
        $this->accountansMove[] = $accountansMove;

        return $this;
    }

    /**
     * Remove accountansMove
     *
     * @param \BackendBundle\Entity\AccountantMove $accountansMove
     */
    public function removeAccountansMove(\BackendBundle\Entity\AccountantMove $accountansMove)
    {
        $this->accountansMove->removeElement($accountansMove);
    }

    /**
     * Get accountansMove
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccountansMove()
    {
        return $this->accountansMove;
    }

    /**
     * Add accounts
     *
     * @param \BackendBundle\Entity\Account $accounts
     * @return Company
     */
    public function addAccount(\BackendBundle\Entity\Account $accounts)
    {
        $this->accounts[] = $accounts;

        return $this;
    }

    /**
     * Remove accounts
     *
     * @param \BackendBundle\Entity\Account $accounts
     */
    public function removeAccount(\BackendBundle\Entity\Account $accounts)
    {
        $this->accounts->removeElement($accounts);
    }

    /**
     * Get accounts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccounts()
    {
        return $this->accounts;
    }
}
