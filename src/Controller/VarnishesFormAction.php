<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Server;
use Snowdog\DevTest\Model\ServerManager;
use Snowdog\DevTest\Model\ServerWebsiteRelationManager;

class VarnishesFormAction
{
    
    /**
     * @var ServerManager
     */
    private $serverManager;

    /**
     * @var Server
     */
    private $server;

    /**
     * @var ServerWebsiteRelationManager
     */
    private $serverWebsiteRelationManager;

    public function __construct(
        UserManager $userManager, 
        ServerManager $serverManager, 
        Server $server,
        ServerWebsiteRelationManager $serverWebsiteRelationManager
    ) {
        $this->serverManager = $serverManager;
        $this->server = $server;
        $this->userManager = $userManager;
        $this->serverWebsiteRelationManager = $serverWebsiteRelationManager;
    }

    public function execute( )
    {
        if (isset($_SESSION['login'])) {
            require __DIR__ . '/../view/varnish.phtml';
            return;
        }

        header('Location: /');
        return;
    }

    public function getVarnishes()
    {
        if (isset($_SESSION['login'])) {
            $user = $this->userManager->getByLogin($_SESSION['login']);
            return $this->serverManager->getAllByUser($user);
        }
        return [];
    }

    public function getAssignedWebsiteIds($server)
    {
        $websitesId = [];
        if (isset($_SESSION['login'])) {
            $user = $this->userManager->getByLogin($_SESSION['login']);
            foreach ($this->serverWebsiteRelationManager->getAllByServer($server) as $web) {
                $websitesId[] = $web->website_id;
            }
            return $websitesId;
        }
        return [];
    }

    public function getWebsites()
    {
        if (isset($_SESSION['login'])) {
            $user = $this->userManager->getByLogin($_SESSION['login']);
            return $this->serverWebsiteRelationManager->getServWebRelationDataByUser($user);
        }
        return [];
    }

}