<?php
namespace Tvce\Audio;

use Tvce\Path;
use Tvce\SocketClientInterface;

class AudioService
{
    /**
     * @var string
     */
    const ROUTE = '/audio/';

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
     * Envia uma mensagem de audio
     * @param $destinyNumber
     * @param $urlAudio
     * @param bool $isUserResponse
     * @return mixed
     */
    public function send($destinyNumber, $urlAudio, $isUserResponse = false)
    {
        $data = [
            'numero_destino' => $destinyNumber,
            'url_audio' => $urlAudio,
            'resposta_usuario' => $isUserResponse
        ];
        $path = new Path([self::ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * Busca uma mensagem de audio pelo seu ID
     * @param $id
     * @return string
     */
    public function getAudio($id)
    {
        $path = new Path([self::ROUTE, $id]);
        return $this->client->get($path->build());
    }

    /**
     * Relatório de mensagens de Audio
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
}