<?php
namespace Tvce;

class SocketClient implements SocketClientInterface
{
    /**
     * @var resource
     */
    private $socket;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $host;

    /**
     * SocketClient constructor.
     * @param $accessToken
     * @param $host
     * @param int $port
     * @throws \HttpSocketException
     */
    public function __construct($accessToken, $host, $port = 80)
    {
        $ipAddress = gethostbyname($host);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if($socket === false) {
            throw new \HttpSocketException(sprintf("Socket create error: ", socket_strerror(socket_last_error())));
        }

        $connect = socket_connect($socket, $ipAddress, $port);
        if(! $connect) {
            throw new \HttpSocketException(sprintf("Socket connect error: ", socket_strerror(socket_last_error($socket))));
        }

        $this->accessToken = $accessToken;
        $this->host   = $host;
        $this->socket = $socket;
    }

    /**
     * @method GET
     * @param $path
     * @param array $params
     * @return string
     */
    public function get($path, $params = [])
    {
        $query = $this->query($params);
        $message = $this->request(Http::GET, $path, $query);
        $this->defaultHeaders($message);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method POST
     * @param $path
     * @param array $params
     * @return string
     */
    public function post($path, $data)
    {
        $message = $this->request(Http::POST, $path);
        $this->defaultHeaders($message);
        $message .= $this->serialize($data);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method PUT
     * @param $path
     * @param array $params
     * @return string
     */
    public function put($path, $data)
    {
        $message = $this->request(Http::PUT, $path);
        $this->defaultHeaders($message);
        $message .= $this->serialize($data);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method DELETE
     * @param $path
     * @return string
     */
    public function delete($path)
    {
        $message = $this->request(Http::DELETE, $path);
        $this->defaultHeaders($message);

        $this->write($message);
        return $this->read();
    }

    /**
     * @param string $verb
     * @param string $path
     * @param string|null $query
     * @return string
     */
    private function request($verb, $path, $query = null)
    {
        return sprintf(
            "%s %s%s %s%s",
            $verb,
            $path,
            $query,
            Http::VERSION,
            Http::HTTP_HEADERS_SEPARATOR
        );
    }

    /**
     * @param $params
     * @return string
     */
    private function query($params)
    {
        $query = '';
        if(! empty($params)) {
            $query = '?' . http_build_query($params);
        }
        return $query;
    }

    /**
     * @param $data
     * @return string
     * @throws \Exception
     */
    private function serialize($data)
    {
        if(! is_array($data)) {
            throw new \Exception("Form data should be a array.");
        }
        return json_encode($data);
    }

    /**
     * @param $message
     */
    private function defaultHeaders(&$message)
    {
        $message .= sprintf("Host: %s" . Http::HTTP_HEADERS_SEPARATOR, $this->host);
        $message .= "Content-type: application/json" . Http::HTTP_HEADERS_SEPARATOR;
        $message .= sprintf("Access-Token: %s" . Http::HTTP_HEADERS_SEPARATOR . Http::HTTP_HEADERS_SEPARATOR, $this->accessToken);
    }

    /**
     * @method write
     * @param $message
     * @return int
     * @throws \Exception
     */
    private function write($message)
    {
        $ret = socket_write($this->socket, $message);
        if ($ret === false) {
            throw new \HttpSocketException("Socket write error: " . socket_last_error());
        }
        return $ret;
    }

    /**
     * @method read
     * @param int $length
     * @return string
     * @throws \HttpSocketException
     */
    private function read($length = 1024)
    {
        $data = socket_read($this->socket, $length);
        if ($data === false) {
            throw new \HttpSocketException("Socket read error: " . socket_last_error());
        }
        return $this->stringfy($data);
    }

    /**
     * @method stringfy
     * @param $data
     * @return string
     */
    private function stringfy($data)
    {
        $pattern = sprintf('/%s%s/', Http::HTTP_HEADERS_SEPARATOR, Http::HTTP_HEADERS_SEPARATOR);
        if (preg_match($pattern, $data)) {
            $values = preg_split($pattern, $data);
            if(isset($values[1])) {
               $data = $values[1];
            }
        }
        return $data;
    }

    /**
     * @method close
     */
    public function close()
    {
        socket_close($this->socket);
    }

    /**
     * @method SocketClient destruct
     */
    public function __destruct()
    {
        if(is_resource($this->socket)) {
            socket_close($this->socket);
        }
    }
}