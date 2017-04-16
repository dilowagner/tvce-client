<?php
namespace Tvce\Call;

use Tvce\Path;
use Tvce\SocketClientInterface;

class CallService
{
    /**
     * @var string
     */
    const ROUTE = '/chamada/';

    /**
     * @var SocketClientInterface
     */
    private $client;

    /**
     * Service constructor.
     * @param SocketClientInterface $client
     */
    public function __construct(SocketClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $originNumber
     * @param string $destinyNumber
     * @param bool $isSaveAudio
     * @param string $originBina
     * @param string $destinyBina
     * @param string $tags
     * @return mixed
     */
    public function call($originNumber, $destinyNumber, $isSaveAudio = false, $originBina = null, $destinyBina = null, $tags = null)
    {
        $data = [
            'numero_origem'  => $originNumber,
            'numero_destino' => $destinyNumber,
            'gravar_audio'   => $isSaveAudio,
            'bina_origem'    => $originBina,
            'bina_destino'   => $destinyBina,
            'tags'           => $tags
        ];
        $path = new Path([self::ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function finish($id)
    {
        $path = new Path([self::ROUTE, $id]);
        return $this->client->delete($path->build());
    }

    /**
     * @param $id
     * @return string
     */
    public function getCallById($id)
    {
        $path = new Path([self::ROUTE, $id]);
        return $this->client->get($path->build());
    }

    /**
     * @param $id
     * @return string
     */
    public function record($id)
    {
        $path = new Path([self::ROUTE, $id, '/gravacao']);
        return $this->client->get($path->build());
    }

    /**
     * @param \DateTime $beginDate
     * @param \DateTime $endDate
     * @return string
     */
    public function report(\DateTime $beginDate, \DateTime $endDate)
    {
        $params = [
            'data_inicio' => $beginDate->format('d/m/Y'),
            'data_fim' => $endDate->format('d/m/Y')
        ];

        $path = new Path([self::ROUTE, 'relatorio']);
        return $this->client->get($path->build(), $params);
    }

    /**
     * @param $id
     * @return string
     */
    public function listen($id, $number, $mode)
    {
        $params = [
            'numero' => $number,
            'modo' => $mode
        ];
        $path = new Path([self::ROUTE, $id, '/escuta']);
        return $this->client->get($path->build(), $params);
    }

    /**
     * @param int $id
     * @param string $number
     * @param string $leg
     * @return string
     */
    public function transfer($id, $number, $leg)
    {
        $data = [
            'numero' => $number,
            'perna' => $leg
        ];
        $path = new Path([self::ROUTE, $id]);
        return $this->client->post($path->build(), $data);
    }
}