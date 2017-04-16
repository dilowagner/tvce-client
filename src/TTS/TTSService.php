<?php
namespace Tvce\TTS;

use Tvce\Path;
use Tvce\SocketClientInterface;

class TTSService
{
    /**
     * @var string
     */
    const TTS_ROUTE = '/tts/';

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
     * @param string $destinyNumber
     * @param string $message
     * @param bool $isUserResponse
     * @return mixed
     */
    public function send($destinyNumber, $message, $speed = 0, $isUserResponse = false, $bina = null)
    {
        $data = [
            'numero_destino' => $destinyNumber,
            'mensagem' => $message,
            'velocidade' => $speed,
            'resposta_usuario' => $isUserResponse,
            'bina' => $bina
        ];
        $path = new Path([self::TTS_ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function getTTS($id)
    {
        $path = new Path([self::TTS_ROUTE, $id]);
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

        $path = new Path([self::TTS_ROUTE, 'relatorio']);
        return $this->client->get($path->build(), $params);
    }
}