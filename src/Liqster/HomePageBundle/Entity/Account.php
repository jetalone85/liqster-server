<?php

namespace Liqster\HomePageBundle\Entity;

use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="Liqster\HomePageBundle\Repository\AccountRepository")
 */
class Account implements TaggableInterface
{
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Tag")
     */
    protected $tags;

    protected $tagsText;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

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
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram_image", type="string", length=255, unique=false, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, unique=false)
     */
    private $password;

    /**
     * @var DateTime
     * @ORM\Column(name="date_created", type="datetime", unique=false, nullable=true)
     */
    private $created;

    /**
     * @var DateTime
     * @ORM\Column(name="date_modif", type="datetime", unique=false, nullable=true)
     */
    private $modif;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\User", inversedBy="account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var boolean
     * @ORM\Column(name="payed", type="boolean", unique=false)
     */
    private $payed = false;

    /**
     * @ORM\OneToOne(targetEntity="Cron\CronBundle\Entity\CronJob", mappedBy="account")
     */
    private $cronJob;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\HomePageBundle\Entity\AccountInstagramCache", mappedBy="account")
     */
    private $accountInstagramCache;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\HomePageBundle\Entity\Purchase", mappedBy="account")
     */
    private $purchase;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\HomePageBundle\Entity\Product", inversedBy="account")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $product;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
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
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * @return mixed
     */
    public function getCronJob()
    {
        return $this->cronJob;
    }

    /**
     * @param mixed $cronJob
     */
    public function setCronJob($cronJob)
    {
        $this->cronJob = $cronJob;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return DateTime
     */
    public function getModif(): DateTime
    {
        return $this->modif;
    }

    /**
     * @param DateTime $modif
     */
    public function setModif(DateTime $modif)
    {
        $this->modif = $modif;
    }

    /**
     * {@inheritdoc}
     */
    public function addTag(TagInterface $tag)
    {
        $this->tags[] = $tag;
    }

    /**
     * {@inheritdoc}
     */
    public function removeTag(TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function hasTag(TagInterface $tag)
    {
        return $this->tags->contains($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function getTagNames()
    {
        return empty($this->tagsText) ? [] : array_map('trim', explode(',', $this->tagsText));
    }

    /**
     * @return string
     */
    public function getTagsText()
    {
        $this->tagsText = implode(', ', $this->tags->toArray());

        return $this->tagsText;
    }

    /**
     * @param string
     */
    public function setTagsText($tagsText)
    {
        $this->tagsText = $tagsText;
        $this->updated = new DateTime();
    }

    /**
     * @return mixed
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return bool
     */
    public function isPayed()
    {
        return $this->payed;
    }

    /**
     * @param bool $payed
     */
    public function setPayed(bool $payed)
    {
        $this->payed = $payed;
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
    public function getAccountInstagramCache()
    {
        return $this->accountInstagramCache;
    }

    /**
     * @param mixed $accountInstagramCache
     */
    public function setAccountInstagramCache($accountInstagramCache)
    {
        $this->accountInstagramCache = $accountInstagramCache;
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
}
