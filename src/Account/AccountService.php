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
     * @var string
     */
    const WEBHOOK_ROUTE = '/webhook/';

    /**
     * @var string
     */
    const BALANCE_ROUTE = '/saldo/';

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
     * Consulta saldo atual
     * @return string
     */
    public function balance()
    {
        $path = new Path([self::BALANCE_ROUTE]);
        return $this->client->get($path->build());
    }

    /**
     * Leitura dos dados da minha conta
     * @return string
     */
    public function account()
    {
        $path = new Path([self::ACCOUNT_ROUTE]);
        return $this->client->get($path->build());
    }

    /**
     * Atualiza os dados da minha conta
     * @param string $name
     * @param string $login
     * @param string $password
     * @param null $cpfCnpj
     * @param null $phone
     * @return string
     */
    public function changeAccount($name, $login, $password, $cpfCnpj = null, $phone = null)
    {
        $data = [
            'nome' => $name,
            'login' => $login,
            'senha' => $password,
            'cpf_cnpj' => $cpfCnpj,
            'telefone' => $phone
        ];
        $path = new Path([self::ACCOUNT_ROUTE]);
        return $this->client->put($path->build(), $data);
    }

    /**
     * Gera um relatório com as recargas efetuadas
     * @return string
     */
    public function recharges()
    {
        $path = new Path([self::ACCOUNT_ROUTE, 'recargas']);
        return $this->client->get($path->build());
    }

    /**
     * Gera uma URL para recarga de créditos
     * @return string
     */
    public function urlRecharge($returnUrl)
    {
        $params = ['url_retorno' => $returnUrl];

        $path = new Path([self::ACCOUNT_ROUTE, 'urlrecarga']);
        return $this->client->get($path->build(), $params);
    }

    /**
     * Retorna a lista de webhooks configurados para esta conta
     * @return string
     */
    public function webhooks()
    {
        $path = new Path([self::WEBHOOK_ROUTE]);
        return $this->client->get($path->build());
    }

    /**
     * Apaga um webhook
     * @param string $name
     * @return string
     */
    public function removeWebhooks($name)
    {
        $path = new Path([self::WEBHOOK_ROUTE, $name]);
        return $this->client->delete($path->build());
    }

    /**
     * Cadastra ou atualiza um webhook
     * @param $name
     * @param $url
     * @return string
     */
    public function saveWebhooks($name, $url)
    {
        $data = ['url' => $url];
        $path = new Path([self::WEBHOOK_ROUTE, $name]);
        return $this->client->put($path->build(), $data);
    }
}