<?php

namespace Instaxer;

class Downloader
{
    /**
     * @param $destiny
     * @param $source
     * @internal param $path
     */
    public function drain($destiny, $source)
    {
        file_put_contents($destiny, fopen($source, 'rb'));
    }
}
