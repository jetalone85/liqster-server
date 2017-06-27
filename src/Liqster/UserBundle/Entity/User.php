<?php

namespace Liqster\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Liqster\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\HomePageBundle\Entity\Account", mappedBy="user")
     */
    private $account;

    /**
     * @var boolean
     * @ORM\Column(name="confirmation", type="boolean", unique=false, nullable=false)
     */
    private $confirmation = false;

    public function __construct()
    {
        parent::__construct();
        $this->account = new ArrayCollection();
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
     * @return bool
     */
    public function isConfirmation(): bool
    {
        return $this->confirmation;
    }

    /**
     * @param bool $confirmation
     */
    public function setConfirmation(bool $confirmation)
    {
        $this->confirmation = $confirmation;
    }
}
