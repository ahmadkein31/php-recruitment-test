<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(
        Database $database
    ) {
        $this->database = $database;
    }

    public function __invoke()
    {
        $this->createServerTable();
        $this->createServerWebsiteRelationTable();
    }

    private function createServerTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `server` (
  `server_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_ip` varchar(255) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`server_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `server_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

    private function createServerWebsiteRelationTable()
    {
        $createQuery = <<<SQL
CREATE TABLE `server_website` (
  `server_website_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(11) unsigned NOT NULL,
  `website_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`server_website_id`),
--   UNIQUE KEY `website_id` (`website_id`),
  KEY `server_id` (`server_id`),
  CONSTRAINT `server_website_server_id_fk` FOREIGN KEY (`server_id`) REFERENCES `server` (`server_id`),
  KEY `website_id` (`website_id`),
  CONSTRAINT `server_website_website_id_fk` FOREIGN KEY (`website_id`) REFERENCES `websites` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }

}