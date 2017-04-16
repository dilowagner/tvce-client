<?php
namespace Tvce\Account;

use Tvce\Path;
use Tvce\SocketClientInterface;

class AccountService
{
    /**
     * @var string
     */
    const ACCOUNT_ROUTE = '/conta/';

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
     * @param string $name
     * @param string $login
     * @param string $password
     * @param null $cpfCnpj
     * @param null $phone
     * @return string
     */
    public function create($name, $login, $password, $cpfCnpj = null, $phone = null)
    {
        $data = [
            'nome' => $name,
            'login' => $login,
            'senha' => $password,
            'cpf_cnpj' => $cpfCnpj,
            'telefone' => $phone
        ];
        $path = new Path([self::ACCOUNT_ROUTE]);
        return $this->client->post($path->build(), $data);
    }

    /**
     * @return string
     */
    public function get($id)
    {
        $path = new Path([self::ACCOUNT_ROUTE, $id]);
        return $this->client->get($path->build());
    }

    /**
     * @param $id
     * @return string
     */
    public function remove($id)
    {
        $path = new Path([self::ACCOUNT_ROUTE, $id]);
        return $this->client->delete($path->build());
    }

    /**
     * @return string
     */
    public function update($id, $name, $login, $password, $cpfCnpj = null, $phone = null)
    {
        $data = [
            'nome' => $name,
            'login' => $login,
            'senha' => $password,
            'cpf_cnpj' => $cpfCnpj,
            'telefone' => $phone
        ];

        $path = new Path([self::ACCOUNT_ROUTE, $id]);
        return $this->client->put($path->build(), $data);
    }

    /**
     * @return string
     */
    public function report()
    {
        $path = new Path([self::ACCOUNT_ROUTE, 'relatorio']);
        return $this->client->get($path->build());
    }
}