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
    private $host;

    /**
     * @var string
     */
    const HTTP_HEADERS_SEPARATOR = "\r\n";

    /**
     * @method SocketClient constructor.
     * @param $host
     * @param $protocol
     * @param int $port
     * @throws \Exception
     */
    public function __construct($host, $protocol = SOL_TCP, $port = 80)
    {
        $ipAddress = gethostbyname($host);
        $socket = socket_create(AF_INET, SOCK_STREAM, $protocol);
        if($socket === false) {
            throw new \HttpSocketException(sprintf("Socket create error: ", socket_strerror(socket_last_error())));
        }

        $connect = socket_connect($socket, $ipAddress, $port);
        if(! $connect) {
            throw new \HttpSocketException(sprintf("Socket connect error: ", socket_strerror(socket_last_error($socket))));
        }

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
        $message  = sprintf("GET %s HTTP/1.1" . self::HTTP_HEADERS_SEPARATOR, $path);
        $message .= sprintf("Host: %s" . self::HTTP_HEADERS_SEPARATOR . self::HTTP_HEADERS_SEPARATOR, $this->host);

        $this->write($message);
        return $this->read();
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