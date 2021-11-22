<?php

namespace Snowdog\DevTest\Model;

class Server
{

    public $server_id;
    public $server_ip;
    public $user_id;

    public function __construct()
    {
        $this->server_id = strval($this->server_id);
        $this->server_ip = strval($this->server_ip);
        $this->user_id = intval($this->user_id);
    }

    /**
     * @return int
     */
    public function getServerId()
    {
        return $this->server_id;
    }

    /**
     * @return string
     */
    public function getServerIP()
    {
        return $this->server_ip;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }
}