<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class ServerWebsiteRelationManager
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    public function getById($serverwebId) {
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM server_website WHERE server_website_id = :serverwebid');
        $query->setFetchMode(\PDO::FETCH_CLASS, ServerWebsiteRelation::class);
        $query->bindParam(':serverwebid', $serverwebId, \PDO::PARAM_STR);
        $query->execute();
        /** @var Website $website */
        $server = $query->fetch(\PDO::FETCH_CLASS);
        return $server;
    }

    public function getAllByServer(Server $server)
    {
        $serverId = $server->getServerId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM server_website WHERE server_id = :serverid');
        $query->bindParam(':serverid', $serverId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, ServerWebsiteRelation::class);
    }

    public function create($serverId, $webid)
    {
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO server_website (server_id, website_id) VALUES (:serverid, :websiteid)');
        $statement->bindParam(':serverid', $serverId, \PDO::PARAM_STR);
        $statement->bindParam(':websiteid', $webid, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function getServWebRelationDataByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT `web`.*, `serv`.* FROM `websites` as `web` LEFT JOIN `server_website` as `servweb` ON `web`.`website_id` = `servweb`.`website_id` LEFT JOIN `server` as `serv` ON `servweb`.`server_id` = `serv`.`server_id` WHERE `web`.`user_id`= :userid');
        $query->bindParam(':userid', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, ServerWebsiteRelation::class);
    }

}