<?php

namespace BlueRock\SSHRemote;

use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Net\SFTP;

class SSHRemote
{
    private $ssh_connection;
    private $sftp;

    public function __construct(string $host, string $username, string $private_key_path, int $port = 22)
    {
        $key = PublicKeyLoader::load(file_get_contents($private_key_path));
        $this->ssh_connection = new SSH2($host, $port);
        if (!$this->ssh_connection->login($username, $key)) {
            throw new \Exception('SSH login failed');
        }
        $this->sftp = new SFTP($this->ssh_connection);
    }

    public function run($commands)
    {
        if (!is_array($commands)) {
            $commands = [$commands];
        }

        $output = [];
        foreach ($commands as $command) {
            $output[] = $this->ssh_connection->exec($command);
        }

        return $output;
    }

    public function put(string $local_file, string $remote_file)
    {
        return $this->sftp->put($remote_file, $local_file, SFTP::SOURCE_LOCAL_FILE);
    }

    public function get(string $remote_file, string $local_file)
    {
        return $this->sftp->get($remote_file, $local_file);
    }
}

