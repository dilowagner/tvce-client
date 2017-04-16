<?php
namespace Tvce\SMS;

use Tvce\Path;
use Tvce\SocketClientInterface;

class SMSService
{
    /**
     * @var string
     */
    const ROUTE = '/sms/';

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
    public function send($destinyNumber, $message, $isUserResponse = false)
    {
        $data = [
            'numero_destino' => $destinyNumber,
            'mensagem' => $message,
            'resposta_usuario' => $isUserResponse
        ];
        $path = new Path([self::ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * @param $id
     * @return string
     */
    public function getSMS($id)
    {
        $path = new Path([self::ROUTE, $id]);
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
}