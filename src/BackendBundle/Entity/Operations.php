<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Operations
 *
 * @ORM\Table(name="operations")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\OperationsRepository")
 */
class Operations
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
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Account", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $accountId;

    /**
     * @var string
     *
     * @ORM\Column(name="deve", type="decimal", precision=10, scale=2)
     */
    private $deve;

    /**
     * @var string
     *
     * @ORM\Column(name="haber", type="decimal", precision=10, scale=2)
     */
    private $haber;

    /**
     * @var AccountantMove
     *
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\AccountantMove", inversedBy="operations", cascade={"persist"})
     * @ORM\JoinColumn(name="accountmove_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotNull()
     */
    private $accountmoveId;

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
     * Set deve
     *
     * @param string $deve
     * @return Operations
     */
    public function setDeve($deve)
    {
        $this->deve = $deve;

        return $this;
    }

    /**
     * Get deve
     *
     * @return string 
     */
    public function getDeve()
    {
        return $this->deve;
    }

    /**
     * Set haber
     *
     * @param string $haber
     * @return Operations
     */
    public function setHaber($haber)
    {
        $this->haber = $haber;

        return $this;
    }

    /**
     * Get haber
     *
     * @return string 
     */
    public function getHaber()
    {
        return $this->haber;
    }



    /**
     * Set accountId
     *
     * @param \BackendBundle\Entity\Account $accountId
     * @return Operations
     */
    public function setAccountId(\BackendBundle\Entity\Account $accountId = null)
    {
        $this->accountId = $accountId;

        return $this;
    }

    /**
     * Get accountId
     *
     * @return \BackendBundle\Entity\Account 
     */
    public function getAccountId()
    {
        return $this->accountId;
    }

    /**
     * Set accountmoveId
     *
     * @param \BackendBundle\Entity\AccountantMove $accountmoveId
     * @return Operations
     */
    public function setAccountmoveId(\BackendBundle\Entity\AccountantMove $accountmoveId = null)
    {
        $this->accountmoveId = $accountmoveId;

        return $this;
    }

    /**
     * Get accountmoveId
     *
     * @return \BackendBundle\Entity\AccountantMove 
     */
    public function getAccountmoveId()
    {
        return $this->accountmoveId;
    }
}
