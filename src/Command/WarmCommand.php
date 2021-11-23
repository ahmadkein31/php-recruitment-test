<?php

namespace Snowdog\DevTest\Command;

use Snowdog\DevTest\Model\PageManager;
use Snowdog\DevTest\Model\WebsiteManager;
use Snowdog\DevTest\Model\ServerManager;
use Snowdog\DevTest\Model\ServerWebsiteRelationManager;
use Symfony\Component\Console\Output\OutputInterface;

class WarmCommand
{
    /**
     * @var WebsiteManager
     */
    private $websiteManager;
    /**
     * @var PageManager
     */
    private $pageManager;
    /**
     * @var ServerWebsiteRelationManager
     */
    private $serverWebsiteRelationManager;
    /**
     * @var ServerManager
     */
    private $serverManager;

    public function __construct(
        WebsiteManager $websiteManager, 
        ServerWebsiteRelationManager $serverWebsiteRelationManager,
        ServerManager $serverManager,
        PageManager $pageManager
    ) {
        $this->websiteManager = $websiteManager;
        $this->serverWebsiteRelationManager = $serverWebsiteRelationManager;
        $this->serverManager = $serverManager;
        $this->pageManager = $pageManager;
    }

    public function __invoke($id, OutputInterface $output)
    {
        $website = $this->websiteManager->getById($id);
        if ($website) {
            $pages = $this->pageManager->getAllByWebsite($website);

            if($serverweb = $this->serverWebsiteRelationManager->getServerByWebsite($website)){
                $serverid = $serverweb->getServerId();
                if($server = $this->serverManager->getById($serverid)){
                    $ip = $server->getServerIp();
                }
            }

            $resolver = new \Old_Legacy_CacheWarmer_Resolver_Method();
            $actor = new \Old_Legacy_CacheWarmer_Actor();
            $actor->setActor(function ($hostname, $ip, $url) use ($output) { 
                $output->writeln('Visited <info>http://' . $hostname . '/' . $url . '</info> via IP: <comment>' . $ip . '</comment>');
            });
            $warmer = new \Old_Legacy_CacheWarmer_Warmer();
            $warmer->setResolver($resolver);
            $warmer->setHostname( !empty($ip)? $ip : $website->getHostname());
            $warmer->setActor($actor);

            foreach ($pages as $page) {
                $warmer->warm($page->getUrl());
            }
        } else {
            $output->writeln('<error>Website with ID ' . $id . ' does not exists!</error>');
        }
    }
}