<?php

namespace Snowdog\DevTest\Model;

use Snowdog\DevTest\Core\Database;

class PageManager
{

    /**
     * @var Database|\PDO
     */
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAllByWebsite(Website $website)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE website_id = :website');
        $query->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_CLASS, Page::class);
    }

    public function create(Website $website, $url)
    {
        $websiteId = $website->getWebsiteId();
        /** @var \PDOStatement $statement */
        $statement = $this->database->prepare('INSERT INTO pages (url, website_id) VALUES (:url, :website)');
        $statement->bindParam(':url', $url, \PDO::PARAM_STR);
        $statement->bindParam(':website', $websiteId, \PDO::PARAM_INT);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    public function getAssociatedPagesCountByUser(User $user)
    {
        $userId = $user->getUserId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT COUNT(*) as associated_page FROM pages WHERE website_id IN (SELECT website_id FROM websites WHERE user_id = :user )');
        $query->setFetchMode(\PDO::FETCH_CLASS, Page::class);
        $query->bindParam(':user', $userId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS)->associated_page;
    }

    public function getPageById(PageVisit $page)
    {
        $pageId =  $page->getPageId();
        /** @var \PDOStatement $query */
        $query = $this->database->prepare('SELECT * FROM pages WHERE page_id = :page_id');
        $query->setFetchMode(\PDO::FETCH_CLASS, Page::class);
        $query->bindParam(':page_id', $pageId, \PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(\PDO::FETCH_CLASS);
    }
}