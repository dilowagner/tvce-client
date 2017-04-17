# tvce-client - Projeto para consumo da API Total Voice

Master:
[![Build Status](https://travis-ci.org/DiloWagner/tvce-client.svg?branch=master)](http://travis-ci.org/#!/DiloWagner/tvce-client)

> ### Funcionalidades

- Gerenciamento das chamadas
- Consulta e envio de SMS
- Consulta e envio de TTS
- Consulta e envio de Audio
- Gerenciamento da Conta
- Gerenciamento da Central

> ### Requisitos

- PHP 5.6+
- Autoloader compatível com a PSR-4

> ### Instalação

Para instalar a biblioteca basta adicioná-la via [composer](https://getcomposer.org/download/)

```composer
composer require tvce/client 1.0.0
```

Ou no composer.json

```json
{
    "tvce/client": "1.0.0"
}
```

> ### Testes

Podemos usar o composer para rodar os testes:

```composer
composer test
```
ou utilizando o .phar

```composer
php composer.phar test
```

> ### Utilização

Para utilizar esta biblioteca, primeiramente você deverá realizar um cadastro no site da [Total Voice API](http://www.totalvoice.com.br/api/).
Após a criação do cadastro será disponibilizado um AccessToken para acesso a API.
Com o AccessToken em mãos será possível realizar as consultas/cadastros conforme documentação da [API](https://api.totalvoice.com.br/doc/#/)

A seguir um pequeno exemplo de como pode ser utilizada esta biblioteca.

> ##### Realiza uma chamada telefônica entre dois números: A e B

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Call\CallService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new CallService($client);
    $response = $service->call('NUMERO-A', 'NUMERO-B');
    echo $response;

    $client->close();

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Consulta de chamada pelo ID

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Call\CallService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new CallService($client);
    $response = $service->getCall('ID_CHAMADA');
    echo $response; // {}

    $client->close();

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```


> ##### Encerra uma chamada ativa

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Call\CallService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new CallService($client);
    $response = $service->finish('ID_CHAMADA');
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Envio de SMS

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\SMS\SMSService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new SMSService($client);
    $response = $service->send('NUMERO-DESTINO', 'MENSAGEM');
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Envio de TTS

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\TTS\TTSService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new TTSService($client);
    $response = $service->send('NUMERO-DESTINO', 'MENSAGEM');
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Envio de Audio

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Audio\AudioService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new AudioService($client);
    $response = $service->send('NUMERO-DESTINO', 'MENSAGEM');
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Configurações de central telefonica

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Central\CentralService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new CentralService($client);
    $response = $service->create();
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

> ##### Gerenciamento dos dados da Conta

```php
<?php
// Considero que já existe um autoloader compatível com a PSR-4 registrado

use Tvce\SocketClient;
use Tvce\Account\AccountService;
use Tvce\SocketClientException;

try {
    
    $client = new SocketClient('{YOUR-ACCESS-TOKEN}', 'api.totalvoice.com.br');
    $service = new AccountService($client);
    $response = $service->get('ID_CONTA');
    echo $response; // {}

} catch(SocketClientException $ex) {
    echo $ex->getMessage();
}
```

Mais informações sobre os métodos disponíveis podem ser encontrados na documentação da [API](https://api.totalvoice.com.br/doc/#/)

> ### Licença

Esta biblioteca segue os termos de uso da [MIT](https://github.com/DiloWagner/tvce-client/blob/master/LICENSE)