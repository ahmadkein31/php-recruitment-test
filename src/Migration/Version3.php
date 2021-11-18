<?php

namespace Snowdog\DevTest\Migration;

use Snowdog\DevTest\Core\Database;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\PageVisitManager;

class Version3
{
    /**
     * @var Database|\PDO
     */
    private $database;
    /**
     * @var PageVisitManager
     */
    private $pageVisitManager;
    /**
     * @var PageManager
     */
    private $pageManager;

    public function __construct(
        Database $database,
        PageVisitManager $pageVisitManager,
        PageManager $pageManager
    ) {
        $this->database = $database;
        $this->pageVisitManager = $pageVisitManager;
        $this->pageManager = $pageManager;
    }

    public function __invoke()
    {
        $this->createVisitedTable();
    }

    private function createVisitedTable()
    {
         $createQuery = <<<SQL
CREATE TABLE `page_visit` (
  `visit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `number_time_visited` int(11),
  `visited_at` DATE NOT NULL,
  PRIMARY KEY (`visit_id`),
  KEY `page_id` (`page_id`),
  CONSTRAINT `page_visit_page_fk` FOREIGN KEY (`page_id`) REFERENCES `pages` (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        $this->database->exec($createQuery);
    }
}