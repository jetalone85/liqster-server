<?php

namespace Liqster\HomePageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\OneToMany(targetEntity="Liqster\HomePageBundle\Entity\Purchase", mappedBy="purchase")
     */
    private $purchase;

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
     * @var integer
     * @ORM\Column(name="period", type="integer", unique=false, nullable=true)
     */
    private $period;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", unique=false, nullable=true)
     */
    private $type;
}
