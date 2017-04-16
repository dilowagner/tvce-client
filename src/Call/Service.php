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
        return $this->client->post('/chamada/', $data);
    }

    public function getCallById($id)
    {
        return $this->client->get('/chamada/' . $id);
    }
}