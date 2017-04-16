<?php
namespace Tvce;

interface SocketClientInterface
{
    /**
     * @param $path
     * @param array $params
     * @return string
     */
    public function get($path, $params = []);

    /**
     * @param $path
     * @param $data
     * @return string
     */
    public function post($path, $data);

    /**
     * @param $path
     * @param $data
     * @return string
     */
    public function put($path, $data);

    /**
     * @param $path
     * @return string
     */
    public function delete($path);
}