<?php
namespace Tvce\Call;

use Tvce\SocketClientInterface;

class Service
{
    private $client;

    public function __construct(SocketClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCallById($id)
    {
        return $this->client->get('/chamada/' . $id);
    }
}