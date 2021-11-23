<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\PageVisitManager;

class IndexAction
{
    protected $totalPages = 0;

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var PageManager
     */
    private $pageManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var PageVisitManager
     */
    private $pageVisitManager;

    public function __construct(
        UserManager $userManager, 
        WebsiteManager $websiteManager, 
        PageManager $pageManager, 
        PageVisitManager $pageVisitManager
    ){
        $this->websiteManager   =  $websiteManager;
        $this->pageManager      =  $pageManager;
        $this->pageVisitManager =  $pageVisitManager;
        if (isset($_SESSION['login'])) {
            $this->user = $userManager->getByLogin($_SESSION['login']);
        }
    }

    protected function getWebsites()
    {
        if($this->user) {
            return $this->websiteManager->getAllByUser($this->user);
        } 
        return [];
    }

    protected function getWebsitePages($website)
    {
        $webPage = [];
        if($website) {
            foreach ($this->pageManager->getAllByWebsite($website) as $key => $page) {
                $webPage[] = $page->url;
                $this->totalPages++;
            }
            return $webPage;
        } 
        return $webPage;
    }

    protected function getTotalAssociatedPages()
    {
        if($this->user) {
            return $this->pageManager->getAssociatedPagesCountByUser($this->user);
        }
        return '';
    }

    protected function getLeastVisitedPage()
    {
        if($this->user) {
            if(!empty($this->pageVisitManager->getLeastVisitedPageByUser($this->user))){
                $page = $this->pageManager->getPageById($this->pageVisitManager->getLeastVisitedPageByUser($this->user));
                return $page->url;
            }
            return '';
        }
        return '';
    }

    protected function getRecentlyVisitedPage()
    {
        if($this->user) {
            if(!empty($this->pageVisitManager->getRecentlyVisitedPageByUser($this->user))){
                $page = $this->pageManager->getPageById($this->pageVisitManager->getRecentlyVisitedPageByUser($this->user));
                return $page->url;
            }
        }
        return '';
    }

    public function execute()
    {
        require __DIR__ . '/../view/index.phtml';
    }
}