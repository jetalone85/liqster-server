<?php

namespace Liqster\Bundle\HomePageBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Liqster\Bundle\HomePageBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\OneToMany(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Purchase", mappedBy="product")
     */
    private $purchase;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\Bundle\HomePageBundle\Entity\DiscountCode", mappedBy="product")
     */
    private $discountCode;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Account", mappedBy="product")
     */
    private $account;

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
     * @ORM\Column(name="period", type="integer", unique=false, nullable=true)
     */
    private $period;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", unique=false, nullable=true)
     */
    private $type;

    /**
     * @var integer
     * @ORM\Column(name="price", type="integer", unique=false, nullable=true)
     */
    private $price;

    /**
     * @var string
     * @ORM\Column(name="image", type="string", unique=false, nullable=true)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(name="status", type="string", unique=false, nullable=true)
     */
    private $status;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", unique=false, nullable=true)
     */
    private $description;

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     * @return Product
     */
    public function setId(Uuid $id): Product
    {
        $this->id = $id;
        return $this;
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
     * @return Product
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
        return $this;
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
     * @return Product
     */
    public function setCreate(DateTime $create): Product
    {
        $this->create = $create;
        return $this;
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
     * @return Product
     */
    public function setModification(DateTime $modification): Product
    {
        $this->modification = $modification;
        return $this;
    }

    /**
     * @return int
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param int $period
     * @return Product
     */
    public function setPeriod(int $period): Product
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Product
     */
    public function setType(string $type): Product
    {
        $this->type = $type;
        return $this;
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
     * @return string
     */
    public function __toString()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getStatus()
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDiscountCode()
    {
        return $this->discountCode;
    }

    /**
     * @param mixed $discountCode
     */
    public function setDiscountCode($discountCode)
    {
        $this->discountCode = $discountCode;
    }
}
