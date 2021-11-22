<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\ServerWebsiteRelationManager;

class CreateWebsiteAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var ServerWebsiteRelationManager
     */
    private $serverWebsiteRelationManager;

    public function __construct(
        UserManager $userManager, 
        WebsiteManager $websiteManager,
        ServerWebsiteRelationManager $serverWebsiteRelationManager
    ) {
        $this->userManager = $userManager;
        $this->websiteManager = $websiteManager;
        $this->serverWebsiteRelationManager = $serverWebsiteRelationManager;
    }

    public function execute()
    {
        $name = $_POST['name'];
        $hostname = $_POST['hostname'];
        $serverId = $_POST['server_id'];

        if(!empty($name) && !empty($hostname)) {
            if (isset($_SESSION['login'])) {
                $user = $this->userManager->getByLogin($_SESSION['login']);
                if ($user) {
                    if ($webid = $this->websiteManager->create($user, $name, $hostname)) {
                        $this->serverWebsiteRelationManager->create($serverId, $webid);
                        $_SESSION['flash'] = 'Website ' . $name . ' added!';
                    }
                }
            }
        } else {
            $_SESSION['flash'] = 'Name and Hostname cannot be empty!';
        }

        header('Location: /');
    }
}