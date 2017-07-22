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
        $this->url = 'https://nameless-taiga-58917.herokuapp.com';
    }

    /**
     * @param string $type
     * @param string $path
     * @param array|null $data
     * @return ResponseInterface
     */
    public function query(string $type, string $path, array $data = []): ResponseInterface
    {
        $request = new Request($type, $this->composeURL($path));
        return $this->client->send($request, $data);
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
