<?php

namespace Snowdog\DevTest\Controller;

class RegisterFormAction
{
    public function execute() 
    {
        if (isset($_SESSION['login'])) {
            require __DIR__ . '/../view/404.phtml';   
        } else {
            require __DIR__ . '/../view/register.phtml';
        }
    }
}