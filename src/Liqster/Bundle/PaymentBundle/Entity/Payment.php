<?php

namespace Liqster\Bundle\PaymentBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="Liqster\Bundle\PaymentBundle\Repository\PaymentRepository")
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
     * @ORM\Column(name="token", type="string", unique=false, nullable=true)
     */
    private $token;

    /**
     * @var string
     * @ORM\Column(name="session", type="string", unique=false, nullable=true)
     */
    private $session;

    /**
     * @var DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $create;

    /**
     * @var string
     * @ORM\Column(name="p24orderid", type="string", unique=false, nullable=true)
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
     * @var string
     * @ORM\Column(name="verify", type="string", unique=false, nullable=true)
     */
    private $verify;

    /**
     * @var DateTime
     * @ORM\Column(name="verify_date", type="datetime", unique=false, nullable=true)
     */
    private $verifyDate;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Purchase", inversedBy="payment")
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id", nullable=true)
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
    public function getCreate()
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
     * @return mixed
     */
    public function getP24OrderId()
    {
        return $this->P24OrderId;
    }

    /**
     * @param mixed $P24OrderId
     */
    public function setP24OrderId($P24OrderId)
    {
        $this->P24OrderId = $P24OrderId;
    }

    /**
     */
    public function getP24Statement()
    {
        return $this->P24Statement;
    }

    /**
     * @param string $P24Statement
     */
    public function setP24Statement(string $P24Statement)
    {
        $this->P24Statement = $P24Statement;
    }

    /**
     * @return string
     */
    public function getP24Sign()
    {
        return $this->P24Sign;
    }

    /**
     * @param string $P24Sign
     */
    public function setP24Sign(string $P24Sign)
    {
        $this->P24Sign = $P24Sign;
    }

    /**
     * @return string
     */
    public function getVerify()
    {
        return $this->verify;
    }

    /**
     * @param string $verify
     */
    public function setVerify(string $verify)
    {
        $this->verify = $verify;
    }

    /**
     * @return mixed
     */
    public function getVerifyDate()
    {
        return $this->verifyDate;
    }

    /**
     * @param mixed $verifyDate
     */
    public function setVerifyDate($verifyDate)
    {
        $this->verifyDate = $verifyDate;
    }
}
