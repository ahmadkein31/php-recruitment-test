<?php

namespace Snowdog\DevTest\Model;

class PageVisit
{

    public $visit_id;
    public $page_id;
    public $visited_at;
    public $number_time_visited;
    
    public function __construct()
    {
        $this->visit_id = intval($this->visit_id);
        $this->page_id = intval($this->page_id);
        $this->visited_at = date ('Y-m-d H:i:s', strtotime($this->visited_at));
        $this->number_time_visited = intval($this->number_time_visited);
    
    }

    /**
     * @return int
     */
    public function getPageVisitId()
    {
        return $this->visit_id;
    }

    /**
     * @return int
     */
    public function getPageId()
    {
        return $this->page_id;
    }

    /**
     * @return string
     */
    public function getVisitedAt()
    {
        return $this->website_id;
    }
    
    /**
     * @return int
     */
    public function getNumberTimeVisited()
    {
        return $this->number_time_visited;
    }
}