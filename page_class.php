<?php

namespace

{
    class Page
    {
        private $page = 0;
        private $maxPage;
        public function __construct($page, $maxPage)
        {
            $this->page = $page;
            $this->maxPage = $maxPage;
        }
        
        public function getMaxPage()
        {
            return $this->maxPage;
        }
        public function getPage()
        {
            return $this->page; //thisは会社・見積・請求など？
        }
        public function getStartPage()
        {
            $start = ($this->getPage() - 1) * 10;
            return  $start;
        }
    }
}
