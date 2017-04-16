<?php
namespace Tvce;

interface SocketClientInterface
{
    /**
     * @param $path
     * @param array $params
     * @return mixed
     */
    public function get($path, $params = []);

    /**
     * @param $path
     * @param $data
     * @return mixed
     */
    public function post($path, $data);

    /**
     * @param $path
     * @param $data
     * @return mixed
     */
    public function put($path, $data);

    /**
     * @param $path
     * @return mixed
     */
    public function delete($path);
}