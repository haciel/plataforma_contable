<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SlipType
 *
 * @ORM\Table(name="slip_type")
 * @ORM\Entity(repositoryClass="BackendBundle\Repository\SlipTypeRepository")
 */
class SlipType
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\AccountantMove", mappedBy="slipe_id" ,cascade={"persist"},orphanRemoval=true)
     */
    private $accountansMove;

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
     * @return SlipType
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
        $this->accountansMove = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add accountansMove
     *
     * @param \BackendBundle\Entity\AccountantMove $accountansMove
     * @return SlipType
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
}
