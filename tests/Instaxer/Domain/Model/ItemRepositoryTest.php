<?php

use Instaxer\Domain\Model\ItemRepository;
use PHPUnit\Framework\TestCase;

class ItemRepositoryTest extends TestCase
{
    public function testConstructAndGetRandomItem()
    {
        $array = [0, 1, 2, 3, 4, 5, 6, 7];
        $itemRepository = new ItemRepository($array);

        $this->assertEquals(true, in_array($itemRepository->getRandomItem()->getItem(), $array, true));
    }

    public function testConstructAndGetFirstItem()
    {
        $array = [0, 1, 2, 3, 4, 5, 6, 7];
        $itemRepository = new ItemRepository($array);

        $this->assertEquals(0, $itemRepository->getFirstItem()->getItem());
    }

    public function testConstructAndGetIntItem()
    {
        $array = [0, 1, 2, 3, 4, 5, 6, 7];
        $itemRepository = new ItemRepository($array);

        $this->assertEquals(4, $itemRepository->getIntItem(4)->getItem());
    }
}
