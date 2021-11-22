<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\User;
use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\ServerManager;

class IndexAction
{

    /**
     * @var WebsiteManager
     */
    private $websiteManager;

    /**
     * @var User
     */
    private $user;

    /**
     * @var ServerManager
     */
    private $serverManager;

    public function __construct(
        UserManager    $userManager, 
        WebsiteManager $websiteManager,
        ServerManager  $serverManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->serverManager  = $serverManager;
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

    protected function getServerList()
    {
        if($this->user) {
            return $this->serverManager->getAllByUser($this->user);
        } 
        return [];
    }

    public function execute()
    {
        if (isset($_SESSION['login'])) {
            require __DIR__ . '/../view/index.phtml';
        } else {
            require __DIR__ . '/../view/404.phtml';
        }
    }
}