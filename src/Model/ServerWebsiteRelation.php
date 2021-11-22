<?php

namespace Snowdog\DevTest\Model;

class ServerWebsiteRelation
{

    public $server_website_id;
    public $server_id;
    public $website_id;
    public $name;
    public $hostname;
    public $user_id;
    public $server_ip;

    public function __construct()
    {
        $this->server_website_id = intval($this->server_website_id);
        $this->server_id = intval($this->server_id);
        $this->website_id = intval($this->website_id);
        $this->name = strval($this->name);
        $this->hostname = strval($this->hostname);
        $this->user_id = intval($this->user_id);
        $this->server_ip = strval($this->server_ip);
    }

    /**
     * @return int
     */
    public function getServerWebsiteRelationId()
    {
        return $this->server_website_id;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->hostname;
    }

    /**
     * @return int
     */
    public function getWebsiteId()
    {
        return $this->website_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getServerIp()
    {
        return $this->server_ip;
    }
    
}