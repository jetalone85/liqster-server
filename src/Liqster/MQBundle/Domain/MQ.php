<?php

namespace Liqster\MQBundle\Domain;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MQ
 * @package Liqster\MQBundle\Domain
 */
class MQ
{
    private $client;

    private $url;

    /**
     * MQ constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->url = 'https://nameless-taiga-58917.herokuapp.com';
    }

    public function query(string $path): ResponseInterface
    {
        $request = new Request('POST', $this->composeURL($path));
        return $this->client->send($request);
    }

    private function composeURL($path): string
    {
        return $this->url . '/' . $path;
    }
}
