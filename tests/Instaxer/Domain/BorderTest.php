<?php

use Instaxer\Domain\Border;
use PHPUnit\Framework\TestCase;

class BorderTest extends TestCase
{
    public function testConstruct()
    {
        $file = __DIR__ . '/white_list_test.data';
        $border = new Border($file);

        $this->assertCount(6, $border->getListContent());
    }

    public function testCheckFalse()
    {
        $file = __DIR__ . '/white_list_test.data';
        $border = new Border($file);

        $this->assertEquals(false, $border->check('element_false'));
    }

    public function testCheckTrue()
    {
        $file = __DIR__ . '/white_list_test.data';
        $border = new Border($file);

        $this->assertEquals(true, $border->check('element1'));
    }

    public function testCount()
    {
        $file = __DIR__ . '/white_list_test.data';
        $border = new Border($file);

        $this->assertEquals(6, $border->count());
    }
}
