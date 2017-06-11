<?php

namespace Instaxer\Request;

use Instagram\API\Response\TagFeedResponse;
use Instaxer\Request;

/**
 * Class TagFeed
 * @package Instaxer\Request
 */
class TagFeed extends Request
{
    /**
     * @param string $tag
     * @return TagFeedResponse
     * @throws \Exception
     */
    public function get(string $tag): TagFeedResponse
    {
        return $this->instaxer->instagram->getTagFeed($tag);
    }
}
