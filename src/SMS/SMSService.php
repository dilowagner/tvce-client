<?php
namespace Tvce\SMS;

use Tvce\Path;
use Tvce\SocketClientInterface;

class SMSService
{
    /**
     * @var string
     */
    const SMS_ROUTE = '/sms/';

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
     * Envia uma mensagem SMS
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
        $path = new Path([self::SMS_ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * Busca uma mensagem SMS pelo seu ID
     * @param $id
     * @return string
     */
    public function getSMS($id)
    {
        $path = new Path([self::SMS_ROUTE, $id]);
        return $this->client->get($path->build());
    }

    /**
     * Relatório de mensagens SMS
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

        $path = new Path([self::SMS_ROUTE, 'relatorio']);
        return $this->client->get($path->build(), $params);
    }
}