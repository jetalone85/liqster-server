<?php

namespace Liqster\HomePageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccountInstagramCache
 *
 * @ORM\Table(name="account_instagram_cache")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\AccountInstagramCacheRepository")
 */
class AccountInstagramCache
{
    /**
     * @var \Ramsey\Uuid\Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $create;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_modification", type="datetime", unique=false, nullable=true)
     */
    private $modification;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", unique=false, nullable=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="value", type="string", unique=false, nullable=true)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\Account", inversedBy="accountInstagramCache")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * @return \Ramsey\Uuid\Uuid
     */
    public function getId(): \Ramsey\Uuid\Uuid
    {
        return $this->id;
    }

    /**
     * @param \Ramsey\Uuid\Uuid $id
     */
    public function setId(\Ramsey\Uuid\Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getCreate(): \DateTime
    {
        return $this->create;
    }

    /**
     * @param \DateTime $create
     */
    public function setCreate(\DateTime $create)
    {
        $this->create = $create;
    }

    /**
     * @return mixed
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * @param mixed $modification
     */
    public function setModification($modification)
    {
        $this->modification = $modification;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
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
}
