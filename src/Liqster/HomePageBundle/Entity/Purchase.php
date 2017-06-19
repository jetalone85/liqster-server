<?php

namespace Liqster\HomePageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\PurchaseRepository")
 */
class Purchase
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
     * @ORM\Column(name="status", type="string", unique=false, nullable=true)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\Product", inversedBy="purchase")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
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
}
