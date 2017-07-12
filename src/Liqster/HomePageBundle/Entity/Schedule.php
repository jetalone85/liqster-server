<?php

namespace Liqster\HomePageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * AccountInstagramCache
 *
 * @ORM\Table(name="schedule")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\AccountInstagramCacheRepository")
 */
class Schedule
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $create;

    /**
     * @var DateTime
     * @ORM\Column(name="date_modification", type="datetime", unique=false, nullable=true)
     */
    private $modification;

    /**
     * @var integer
     * @ORM\Column(name="morning", type="integer", unique=false)
     */
    private $morning = 1;

    /**
     * @var integer
     * @ORM\Column(name="noon", type="integer", unique=false)
     */
    private $noon = 1;

    /**
     * @var integer
     * @ORM\Column(name="afternoon", type="integer", unique=false)
     */
    private $afternoon = 1;

    /**
     * @var integer
     * @ORM\Column(name="evening", type="integer", unique=false)
     */
    private $evening = 1;

    /**
     * @var integer
     * @ORM\Column(name="night", type="integer", unique=false)
     */
    private $night = 1;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\HomePageBundle\Entity\Account", inversedBy="schedule")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=false)
     */
    private $account;

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     */
    public function setId(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @return DateTime
     */
    public function getCreate(): DateTime
    {
        return $this->create;
    }

    /**
     * @param DateTime $create
     */
    public function setCreate(DateTime $create)
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
    public function getMorning(): string
    {
        return $this->morning;
    }

    /**
     * @param string $morning
     */
    public function setMorning(string $morning)
    {
        $this->morning = $morning;
    }

    /**
     * @return string
     */
    public function getNoon(): string
    {
        return $this->noon;
    }

    /**
     * @param string $noon
     */
    public function setNoon(string $noon)
    {
        $this->noon = $noon;
    }

    /**
     * @return string
     */
    public function getAfternoon(): string
    {
        return $this->afternoon;
    }

    /**
     * @param string $afternoon
     */
    public function setAfternoon(string $afternoon)
    {
        $this->afternoon = $afternoon;
    }

    /**
     * @return string
     */
    public function getEvening(): string
    {
        return $this->evening;
    }

    /**
     * @param string $evening
     */
    public function setEvening(string $evening)
    {
        $this->evening = $evening;
    }

    /**
     * @return string
     */
    public function getNight(): string
    {
        return $this->night;
    }

    /**
     * @param string $night
     */
    public function setNight(string $night)
    {
        $this->night = $night;
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

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getId()->getUrn();
    }
}
