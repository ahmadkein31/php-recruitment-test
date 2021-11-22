<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class ServerManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    public function getById($serverId) {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM server WHERE server_id = :serverid');
        $query->setFetchMode(\PDO::FETCH_CLASS, Server::class);
        $query->bindParam(':serverid', $serverId, \PDO::PARAM_STR);
        $query->execute();
        /** @var Website $website */
        $server = $query->fetch(\PDO::FETCH_CLASS);
        return $server;
    }

    public function getAllByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM server WHERE user_id = :user');
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Server::class);
    }

    public function create($userId, $ip)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO server (server_ip, user_id) VALUES (:serverip, :user)');
        $statement->bindParam(':serverip', $ip, \PDO::PARAM_STR);
        $statement->bindParam(':user', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

}