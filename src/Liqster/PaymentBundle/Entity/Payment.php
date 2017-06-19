<?php

namespace Liqster\PaymentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="Liqster\PaymentBundle\Repository\PaymentRepository")
 */
class Payment
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
     * @var string
     * @ORM\Column(name="token", type="string", unique=false, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $create;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\HomePageBundle\Entity\Purchase", inversedBy="purchase")
     * @ORM\JoinColumn(name="purchase_id", referencedColumnName="id", nullable=false)
     */
    private $purchase;
}
