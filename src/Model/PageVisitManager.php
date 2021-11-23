<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageVisitManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByPage(Page $page)
    {
        $pageId = $page->getPageId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM page_visit WHERE page_id = :pageid');
        $query->setFetchMode(\PDO::FETCH_CLASS, PageVisit::class);
        $query->bindParam(':pageid', $pageId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }

    public function create(Page $page, $visitedAt, $numberTimeVisited)
    {
        $pageId = $page->getPageId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO page_visit (page_id, visited_at, number_time_visited) VALUES (:page, :visited_at, :number_time_visited)');
        $statement->bindParam(':page', $pageId, \PDO::PARAM_INT);
        $statement->bindParam(':visited_at', $visitedAt, \PDO::PARAM_STR);
        $statement->bindParam(':number_time_visited', $numberTimeVisited, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function update(Page $page, $visitedAt='', $numberTimeVisited = 1)
    {
        $visitedAt = date('Y-m-d H:i:s');

        $visit = $this->getByPage($page);

        if(empty($visit)) {
            return $this->create($page, $visitedAt, $numberTimeVisited);
        } else { 
            $numberTimeVisited += intVal($visit->number_time_visited);
            /** @var \PDOStatement $statement */
            $statement = $this->database->prepare('UPDATE page_visit SET visited_at = :visited_at, number_time_visited = :number_time_visited WHERE page_id = :pageid');
            $statement->bindParam(':pageid', $visit->page_id, \PDO::PARAM_INT);
            $statement->bindParam(':visited_at', $visitedAt, \PDO::PARAM_STR);
            $statement->bindParam(':number_time_visited', $numberTimeVisited, \PDO::PARAM_INT);
            $statement->execute();
            return $this->database->lastInsertId();
        }
    }

    public function getLeastVisitedPageByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM page_visit WHERE page_id IN ( SELECT page_id as associated_page FROM pages WHERE website_id IN (SELECT website_id FROM websites WHERE user_id = :user ) ) ORDER BY visited_at ASC LIMIT 1 ');
        $query->setFetchMode(\PDO::FETCH_CLASS, PageVisit::class);
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }

    public function getRecentlyVisitedPageByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM page_visit WHERE page_id IN ( SELECT page_id as associated_page FROM pages WHERE website_id IN (SELECT website_id FROM websites WHERE user_id = :user ) ) ORDER BY visited_at DESC LIMIT 1 ');
        $query->setFetchMode(\PDO::FETCH_CLASS, PageVisit::class);
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }
}