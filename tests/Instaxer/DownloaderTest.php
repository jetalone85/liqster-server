<?php

use Instaxer\Downloader;
use PHPUnit\Framework\TestCase;

class DownloaderTest extends TestCase
{
    public function testDrain()
    {
        if (file_exists(__DIR__ . '/../../app/storage/test.jpg')) {
            unlink(__DIR__ . '/../../app/storage/test.jpg');
        }

        $downloader = new Downloader();
        $downloader->drain(__DIR__ . '/../../app/storage/test.jpg', 'https://www.google.pl/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png');

        $this->assertFileExists(__DIR__ . '/../../app/storage/test.jpg');
    }
}
