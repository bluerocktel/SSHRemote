<?php

namespace BlueRock\SSHRemote;

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SCP;

class SSHRemoteFacade
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance(string $host, string $username, string $private_key_path, int $port = 22)
    {
        if (null === self::$instance) {
            self::$instance = new SSHRemote($host, $username, $private_key_path, $port);
        }

        return self::$instance;
    }

    public static function run($commands)
    {
        return self::$instance->run($commands);
    }

    public static function put(string $local_file, string $remote_file)
    {
        return self::$instance->put($local_file, $remote_file);
    }

    public static function get(string $remote_file, string $local_file)
    {
        return self::$instance->get($remote_file, $local_file);
    }
}

