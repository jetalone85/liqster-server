<?php

namespace Liqster\Bundle\HomePageBundle\Entity;

use Beelab\TagBundle\Tag\TaggableInterface;
use Beelab\TagBundle\Tag\TagInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="Liqster\Bundle\HomePageBundle\Repository\AccountRepository")
 */
class Account implements TaggableInterface
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Tag")
     */
    protected $tags;
    protected $tagsText;
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
     * @var boolean
     * @ORM\Column(name="disabled", type="boolean", unique=false, nullable=true)
     */
    private $disabled = false;

    /**
     * @var boolean
     * @ORM\Column(name="likes_run", type="boolean", unique=false, nullable=true)
     */
    private $likesRun = true;

    /**
     * @var boolean
     * @ORM\Column(name="comments_run", type="boolean", unique=false, nullable=true)
     */
    private $commentsRun = true;

    /**
     * @var boolean
     * @ORM\Column(name="payed", type="boolean", unique=false)
     */
    private $payed = false;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\User", inversedBy="account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="Cron\CronBundle\Entity\CronJob", mappedBy="account")
     */
    private $cronJob;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\AccountInstagramCache", mappedBy="account")
     */
    private $accountInstagramCache;

    /**
     * @ORM\OneToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Schedule", mappedBy="account")
     */
    private $schedule;

    /**
     * @ORM\OneToMany(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Purchase", mappedBy="account")
     */
    private $purchase;

    /**
     * @ORM\ManyToOne(targetEntity="Liqster\Bundle\HomePageBundle\Entity\Product", inversedBy="account")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=true)
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", length=4096, unique=false, nullable=true)
     */
    private $comments;

    /**
     * Account constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
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
    public function getCreated()
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
    public function getModif()
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

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @param mixed $schedule
     */
    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return bool
     */
    public function isLikesRun(): bool
    {
        return $this->likesRun;
    }

    /**
     * @param mixed $likesRun
     */
    public function setLikesRun(bool $likesRun)
    {
        $this->likesRun = $likesRun;
    }

    /**
     * @return bool
     */
    public function isCommentsRun(): bool
    {
        return $this->commentsRun;
    }

    /**
     * @param bool $commentsRun
     */
    public function setCommentsRun(bool $commentsRun)
    {
        $this->commentsRun = $commentsRun;
    }
}
