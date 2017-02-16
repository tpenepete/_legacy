<?php

defined('TPT_INIT') or die('access denied');

//var_dump($_POST);die();



if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    
    $products = amz_cart::processCustomProductPOSTData($tpt_vars);
    
    //var_dump($products);die();
    
    foreach($products as $p) {
        amz_cart::add($tpt_vars,  $p);
        $p->getCachedProductImage($tpt_vars);
    }
    
    $bulder_id = 0;
    if(!empty($_POST['short_builder']))
        $bulder_id = intval($_POST['short_builder'], 10);
    $query = 'INSERT INTO `'.DB_DB_TPT_LOGS.'`.`tpt_request_cart` (`ip`, `userid`, `timestamp`, `action`, `products`, `builder`, `cart_totals`)
              VALUES (
              "'.$vars['user']['client_ip'].'",
              '.$vars['user']['userid'].',
              '.time().',
              "'.$task.'",
              "'.mysql_real_escape_string(serialize(amz_cart::$products)).'",
              '.$bulder_id.',
              "'.mysql_real_escape_string(serialize(amz_cart::$totals)).'"
              )';
    //var_dump($query);die();
    $tpt_vars['db']['handler']->query($query);
    
    
    
    
    include(TPT_PAGES_DIR.DIRECTORY_SEPARATOR.'tpt-short-builder-add-upgrade.php');
    //var_dump($tpt_vars['template']['content']);die();
    
    $tpt_vars['environment']['ajax_result']['result'] = array('main_content'=>$tpt_vars['template']['content']);
    $res = addslashes(json_encode($tpt_vars['environment']['ajax_result']));
    $tpt_vars['environment']['ajax_result']['exec_script'][] = <<< EOT
    init_upsell('$res');
EOT;
    $tpt_vars['environment']['isAjax'] = true;
}