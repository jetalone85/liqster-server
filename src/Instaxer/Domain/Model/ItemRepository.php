<?php

namespace Instaxer\Domain\Model;

use Instaxer\Domain\Repository;

/**
 * Class ItemRepository
 *
 * @package Instaxer\Domain\Model
 */
class ItemRepository extends Repository
{
    /**
     * @var array
     */
    protected $arrayOfTags;

    /**
     * ItemRepository constructor.
     *
     * @param array $arrayOfTags
     */
    public function __construct(array $arrayOfTags)
    {
        $this->arrayOfTags = $arrayOfTags;
    }

    /**
     * @return Item
     */
    public function getRandomItem(): Item
    {
        return new Item($this->arrayOfTags[random_int(0, count($this->arrayOfTags) - 1)]);
    }

    /**
     * @return Item
     */
    public function getFirstItem(): Item
    {
        return new Item($this->arrayOfTags[0]);
    }

    /**
     * @param $int
     * @return Item
     */
    public function getIntItem($int): Item
    {
        return new Item($this->arrayOfTags[$int]);
    }
}
