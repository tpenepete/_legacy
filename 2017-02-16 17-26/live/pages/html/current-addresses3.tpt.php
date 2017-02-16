<?php
defined('TPT_INIT') or die('access denied');
    
    $countries_module                  = getModule($tpt_vars, "Countries");
    $countries = $countries_module->moduleData['id'];

    //var_dump($tpt_vars['user']['addresses']);die();
    //var_dump($tpt_vars);die();
    //global $tpt_vars;
    $tpt_imagesurl = TPT_IMAGES_URL;

    $action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
    $shipping_address = $tpt_vars['user']['addresses']['shipping'];
    $payment_address = $tpt_vars['user']['addresses']['payment'];
    
    $links = array('<a title="Update Profile" style="font-size:14px; text-decoration:none;" href="'.$tpt_vars['url']['handler']->wrap($tpt_vars, '/shipping-details').'"><img border="0" src="images/cart-change-address.png"></a>');
    
    $address_links = implode(" | ", $links);
    $i='right';
    
    $address_names = implode(' ', array_filter(array($shipping_address['fname'], $shipping_address['lname'])));
    $address_company = $shipping_address['company'];
    $address_name = $shipping_address['address_name'];
    $address_addr = '<div class="height-22" style="">'.implode('</div><div class="height-22" style="">', array_filter(array($shipping_address['address1'], $shipping_address['address2']))).'</div>';
    /*
    $countries = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_countries', 'id,name,states_source', '', 'id', false);
    $address_country = $countries[$shipping_address['country']]['name'];
    $address_state = '';
    if(preg_match('#^\{.*\}$#', $countries[$shipping_address['country']]['states_source'], $mtch)) {
        $address_state = $shipping_address['state'];
    } else {
        $stvals = $tpt_vars['db']['handler']->getData($tpt_vars, $countries[$shipping_address['country']]['states_source'], 'id,state,state_code', '', 'id', false);
        $address_state = $stvals[$shipping_address['state']]['state'];
        if($shipping_address['country'] == 1)
            $address_state = $stvals[$shipping_address['state']]['state_code'];
    }
    */
    $address_country = $countries[$shipping_address['country']]['name'];
    $address_state = $countries_module->getStateName($tpt_vars, $shipping_address['country'], $shipping_address['state']);
    $address_city = $shipping_address['city'];
    $address_zip = $shipping_address['zip'];
    $address_phone = $shipping_address['phone'];
    $address_html = '';
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'address-box2.tpt.php');
    $shipping_html .= $address_html;
    
    
    $links = array('<a title="Update Profile" style="font-size:14px; text-decoration:none;" href="'.$tpt_vars['url']['handler']->wrap($tpt_vars, '/shipping-details').'"><img border="0" src="images/cart-change-address.png"></a>');
    
    $address_links = implode(" | ", $links);
    $i='left';
    
    $address_names = implode(' ', array_filter(array($payment_address['fname'], $payment_address['lname'])));
    $address_company = $payment_address['company'];
    $address_name = $payment_address['address_name'];
    $address_addr = '<div class="height-22" style="">'.implode('</div><div class="height-22" style="">', array_filter(array($payment_address['address1'], $payment_address['address2']))).'</div>';
    //$countries = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_countries', 'id,name,states_source', '', 'id', false);
    /*
    $address_country = $countries[$payment_address['country']]['name'];
    $address_state = '';
    if(preg_match('#^\{.*\}$#', $countries[$payment_address['country']]['states_source'], $mtch)) {
        $address_state = $payment_address['state'];
    } else {
        $stvals = $tpt_vars['db']['handler']->getData($tpt_vars, $countries[$payment_address['country']]['states_source'], 'id,state,state_code', '', 'id', false);
        $address_state = $stvals[$payment_address['state']]['state'];
        if($payment_address['country'] == 1)
            $address_state = $stvals[$payment_address['state']]['state_code'];
    }
    */
    $address_country = $countries[$shipping_address['country']]['name'];
    $address_state = $countries_module->getStateName($tpt_vars, $payment_address['country'], $payment_address['state']);
    $address_city = $payment_address['city'];
    $address_zip = $payment_address['zip'];
    $address_phone = $payment_address['phone'];
    $address_html = '';
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'address-box2.tpt.php');
    $payment_html .= $address_html;
    
$tpt_vars['template_data']['head'][] = <<< EOT
EOT;
    

?>