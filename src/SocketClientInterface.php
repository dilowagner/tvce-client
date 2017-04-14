<?php
namespace Tvce;

interface SocketClientInterface
{
    public function get($path, $params = []);
}