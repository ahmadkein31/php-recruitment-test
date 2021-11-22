<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\ServerManager;

class VarnishesAction
{
    /**
     * @var ServerManager
     */
    private $serverManager;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        UserManager $userManager,
        ServerManager $serverManager
    ) {
        $this->serverManager = $serverManager;
        $this->userManager = $userManager;
    }

    public function execute()
    {
        if (isset($_SESSION['login'])) {
            $serverip = $_POST['ip'];
            
            if(!empty($serverip)) {
                $user = $this->userManager->getByLogin($_SESSION['login']);
                $this->serverManager->create($user->getUserId(), $serverip);
                $_SESSION['flash'] = 'IP/Server Added Successfully!';
            }
            
            header('Location: /varnish');
            return;
        }
        header('Location: /');
        return;
    }
}