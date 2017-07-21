<?php

namespace Liqster\HomePageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\UserRepository")
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
     * @var string
     *
     * @ORM\Column(name="google_id", type="string", nullable=true)
     */
    private $googleID;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\HomePageBundle\Entity\Account", mappedBy="user")
     */
    private $account;

    /**
     * @var boolean
     * @ORM\Column(name="confirmation", type="boolean", unique=false, nullable=false)
     */
    private $confirmation = false;

    /**
     * User constructor.
     */
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

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getGoogleID()
    {
        return $this->googleID;
    }

    /**
     * @param mixed $googleID
     */
    public function setGoogleID($googleID)
    {
        $this->googleID = $googleID;
    }
}
