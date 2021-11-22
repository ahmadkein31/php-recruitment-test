<?php

namespace Snowdog\DevTest\Controller;

class LoginFormAction
{

    public function execute()
    {
        if (isset($_SESSION['login'])) {
            require __DIR__ . '/../view/404.phtml';
        } else {
            require __DIR__ . '/../view/login.phtml';
        }
    }
}