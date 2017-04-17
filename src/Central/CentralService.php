<?php
namespace Tvce\Central;

use Tvce\Path;
use Tvce\SocketClientInterface;

class CentralService
{
    /**
     * @var string
     */
    const RAMAL_ROUTE = '/ramal/';

    /**
     * @var string
     */
    const WEBPHONE_ROUTE = '/webphone/';

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
     * Cria um novo ramal
     * @return string
     */
    public function create($ramal = null, $login = null, $password = null, $bina = null, $isExternal = false, $isCelular = false, $isSaveAudio = false, $isAccessRecording = false)
    {
        $data = [
            'ramal'  => $ramal,
            'login' => $login,
            'senha' => $password,
            'bina' => $bina,
            'ligacao_externa' => $isExternal,
            'ligacao_celular' => $isCelular,
            'gravar_audio' => $isSaveAudio,
            'acesso_gravacoes' => $isAccessRecording
        ];

        $path = new Path([self::RAMAL_ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * Remove um Ramal
     * @param string $name
     * @return string
     */
    public function remove($id)
    {
        $path = new Path([self::RAMAL_ROUTE, $id]);
        return $this->client->delete($path->build());
    }

    /**
     * Busca uma Ramal pelo seu ID
     * @return string
     */
    public function get($id)
    {
        $path = new Path([self::RAMAL_ROUTE, $id]);
        return $this->client->get($path->build());
    }

    /**
     * Atualiza um ramal
     * @return string
     */
    public function update($id, $ramal = null, $login = null, $password = null, $bina = null, $isExternal = false, $isCelular = false, $isSaveAudio = false, $isAccessRecording = false)
    {
        $data = [
            'ramal'  => $ramal,
            'login' => $login,
            'senha' => $password,
            'bina' => $bina,
            'ligacao_externa' => $isExternal,
            'ligacao_celular' => $isCelular,
            'gravar_audio' => $isSaveAudio,
            'acesso_gravacoes' => $isAccessRecording
        ];

        $path = new Path([self::RAMAL_ROUTE, $id]);
        return $this->client->put($path->build(), $data);
    }

    /**
     * Relatório de mensagens de Ramal
     * @return string
     */
    public function report()
    {
        $path = new Path([self::RAMAL_ROUTE, 'relatorio']);
        return $this->client->get($path->build());
    }

    /**
     * Requisita a URL do webphone de um ramal
     * @param $type
     * @param $idRamal
     * @param $ramal
     * @param $callFor
     * @param $isClosedEnd
     * @return string
     */
    public function webphone($type, $idRamal, $ramal, $callFor, $isClosedEnd)
    {
        $params = [
            'tipo' => $type,
            'id_ramal' => $idRamal,
            'ramal' => $ramal,
            'ligar_para' => $callFor,
            'fechar_fim' => $isClosedEnd
        ];

        $path = new Path([self::WEBPHONE_ROUTE]);
        return $this->client->get($path->build(), $params);
    }
}