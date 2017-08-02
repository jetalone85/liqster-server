<?php

namespace Liqster\Bundle\HomePageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * DiscountCode
 *
 * @ORM\Table(name="discount_code")
 * @ORM\Entity(repositoryClass="Liqster\Bundle\HomePageBundle\Repository\DiscountCodeRepository")
 */
class DiscountCode
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
     * @var float
     * @ORM\Column(name="promotion", type="float", unique=false)
     */
    private $promotion;

    /**
     * @var float
     * @ORM\Column(name="key", type="string", unique=true)
     */
    private $key;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Purchase", inversedBy="discountCode")
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id", nullable=true)
     */
    private $purchase;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Product", inversedBy="discountCode")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Account", inversedBy="discountCode")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
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
     * @return DateTime
     */
    public function getModification()
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
     * @return float
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param float $promotion
     */
    public function setPromotion(float $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
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
