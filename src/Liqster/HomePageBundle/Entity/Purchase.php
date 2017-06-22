<?php

namespace Liqster\HomePageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\PurchaseRepository")
 */
class Purchase
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
     * @var string
     * @ORM\Column(name="status", type="string", unique=false, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\Product", inversedBy="purchase")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="SET NULL")
     */
    private $product;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\PaymentBundle\Entity\Payment", mappedBy="purchase")
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\Account", inversedBy="purchase")
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
     * @return DateTime
     */
    public function getModification(): DateTime
    {
        return $this->modification;
    }

    /**
     * @param DateTime $modification
     */
    public function setModification(DateTime $modification)
    {
        $this->modification = $modification;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param mixed $payment
     */
    public function setPayment($payment)
    {
        $this->payment = $payment;
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
