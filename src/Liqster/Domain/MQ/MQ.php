<?php

namespace Liqster\Domain\MQ;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MQ
 *
 * @package Liqster\Bundle\MQBundle\Domain
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
     * @var array
     */
    private $base = [
        'https://nameless-taiga-58917.herokuapp.com',
        'https://afternoon-meadow-17645.herokuapp.com'
    ];

    /**
     * MQ constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->url = 'http://localhost:8000';
//        $this->url = 'http://liqster.pl';
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
