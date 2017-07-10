<?php

namespace Liqster\Domain\MQ;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MQ
 *
 * @package Liqster\MQBundle\Domain
 */
class MQ
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    /**
     * MQ constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->url = 'http://localhost:8001';
//        $this->url = 'https://nameless-taiga-58917.herokuapp.com';
    }

    /**
     * @param string $path
     * @return ResponseInterface
     */
    public function query(string $path): ResponseInterface
    {
        $request = new Request('POST', $this->composeURL($path));
        return $this->client->send($request);
    }

    /**
     * @param $path
     * @return string
     */
    private function composeURL($path): string
    {
        return $this->url . '/' . $path;
    }
}
