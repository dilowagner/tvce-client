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
     * @var string
     */
    const HTTP_HEADERS_SEPARATOR = "\r\n";

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
     * @method get
     * @param $path
     * @param array $params
     * @return string
     */
    public function get($path, $params = [])
    {
        $query = $this->query($params);
        $message  = sprintf("GET %s%s HTTP/1.1" . self::HTTP_HEADERS_SEPARATOR, $path, $query);
        $this->defaultHeaders($message);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method get
     * @param $path
     * @param array $params
     * @return string
     */
    public function post($path, $data)
    {
        $message = sprintf("POST %s HTTP/1.1" . self::HTTP_HEADERS_SEPARATOR, $path);
        $this->defaultHeaders($message);
        $message .= $this->serialize($data);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method get
     * @param $path
     * @param array $params
     * @return string
     */
    public function put($path, $data)
    {
        $message = sprintf("PUT %s HTTP/1.1" . self::HTTP_HEADERS_SEPARATOR, $path);
        $this->defaultHeaders($message);
        $message .= $this->serialize($data);

        $this->write($message);
        return $this->read();
    }

    /**
     * @method get
     * @param $path
     * @param array $params
     * @return string
     */
    public function delete($path)
    {
        $message = sprintf("DELETE %s HTTP/1.1" . self::HTTP_HEADERS_SEPARATOR, $path);
        $this->defaultHeaders($message);

        $this->write($message);
        return $this->read();
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
        $message .= sprintf("Host: %s" . self::HTTP_HEADERS_SEPARATOR, $this->host);
        $message .= "Content-type: application/json" . self::HTTP_HEADERS_SEPARATOR;
        $message .= sprintf("Access-Token: %s" . self::HTTP_HEADERS_SEPARATOR . self::HTTP_HEADERS_SEPARATOR, $this->accessToken);
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
        $pattern = sprintf('/%s%s/', self::HTTP_HEADERS_SEPARATOR, self::HTTP_HEADERS_SEPARATOR);
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