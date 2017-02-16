<?php

defined('TPT_INIT') or die('access denied');

class tpt_adminTab {
    
    var $title;
    var $content;
    var $pagination;
    
    function __construct(&$vars, $title, $content, $pagination='') {
        $this->title = $title;
        $this->content = $content;
        $this->pagination = $pagination;
        
    }
}