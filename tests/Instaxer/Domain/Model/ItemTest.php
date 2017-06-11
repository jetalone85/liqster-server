<?php

use Instaxer\Domain\Model\Item;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testConstructAndGetItem()
    {
        $item = new Item('book');

        $this->assertEquals('book', $item->getItem());
    }
}
