<?php
namespace Tvce\Call;

use Tvce\SocketClientInterface;

class Service
{
    const PATH = '/chamada/';
    private $client;

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
        return $this->client->post(self::PATH, $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function finish($id)
    {
        return $this->client->delete(self::PATH . $id);
    }

    /**
     * @param $id
     * @return string
     */
    public function getCallById($id)
    {
        return $this->client->get(self::PATH . $id);
    }

    /**
     * @param $id
     * @return string
     */
    public function record($id)
    {
        $path = sprintf('%s$s$s', self::PATH, $id, '/gravacao');
        return $this->client->get($path);
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

        $path = sprintf('%s%s', self::PATH, 'relatorio');
        return $this->client->get($path, $params);
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
        $path = sprintf('%s$s$s', self::PATH, $id, '/escuta');
        return $this->client->get($path, $params);
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
        return $this->client->post(self::PATH . $id, $data);
    }
}