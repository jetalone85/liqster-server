<?php

namespace Liqster\PaymentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="Liqster\PaymentBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var string
     * @ORM\Column(name="token", type="string", unique=false, nullable=false)
     */
    private $token;

    /**
     * @var string
     * @ORM\Column(name="session", type="string", unique=false, nullable=false)
     */
    private $session;


    /**
     * @var string
     * @ORM\Column(name="p24order_id", type="string", unique=false, nullable=true)
     */
    private $P24OrderId;

    /**
     * @var string
     * @ORM\Column(name="p24statement", type="string", unique=false, nullable=true)
     */
    private $P24Statement;

    /**
     * @var string
     * @ORM\Column(name="p24sign", type="string", unique=false, nullable=true)
     */
    private $P24Sign;

    /**
     * @var DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $create;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\HomePageBundle\Entity\Purchase", inversedBy="purchase")
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id", nullable=false)
     */
    private $purchase;

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
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
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
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * @param mixed $purchase
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * @return string
     */
    public function getSession(): string
    {
        return $this->session;
    }

    /**
     * @param string $session
     */
    public function setSession(string $session)
    {
        $this->session = $session;
    }

    /**
     * @return string
     */
    public function getP24OrderId(): string
    {
        return $this->P24OrderId;
    }

    /**
     * @param string $P24OrderId
     */
    public function setP24OrderId(string $P24OrderId)
    {
        $this->P24OrderId = $P24OrderId;
    }

    /**
     * @return mixed
     */
    public function getP24Statement()
    {
        return $this->P24Statement;
    }

    /**
     * @param mixed $P24Statement
     */
    public function setP24Statement($P24Statement)
    {
        $this->P24Statement = $P24Statement;
    }

    /**
     * @return mixed
     */
    public function getP24Sign()
    {
        return $this->P24Sign;
    }

    /**
     * @param mixed $P24Sign
     */
    public function setP24Sign($P24Sign)
    {
        $this->P24Sign = $P24Sign;
    }
}
