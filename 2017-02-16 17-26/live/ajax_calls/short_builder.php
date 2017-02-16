<?php

defined('TPT_INIT') or die('access denied');

//var_dump($_POST);die();

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    include(TPT_PAGES_DIR.DIRECTORY_SEPARATOR.'tpt-short-builder.php');
    //var_dump($tpt_vars['template']['content']);die();
    
    $tpt_vars['environment']['ajax_result']['update_elements'] = array('main_content'=>$tpt_vars['template']['content']);
    $tpt_vars['environment']['isAjax'] = true;
}