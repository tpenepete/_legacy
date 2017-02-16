<?php
defined('TPT_INIT') or die('access denied');
$add_address_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');

//var_dump($tpt_vars['user']['addresses']);die();
$address_html = '';
$i='left';

$cadr = array('payment'=>0, 'shipping'=>1);
$cadr = array_intersect_key($tpt_vars['user']['addresses'], $cadr);
foreach($cadr as $address) {
    $links = array();

//$an = base64_encode($address['address_name']);

$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');
$urlparam = '';
if($address['address_name'] == 'shipping') {
    $urlparam = '?shipping=1';
    $address = $tpt_vars['user']['addresses']['shipping_data'];
}

if(empty($address['fname']) || empty($address['lname'])) {
    $address = array(
                'id'=>1,
                'address_name'=>'shipping',
                'title'=>'',
                'fname'=>'',
                'mname'=>'',
                'lname'=>'',
                'address1'=>'(Same as Billing/Not Provided)',
                'address2'=>'',
                'address3'=>'',
                'country'=>'',
                'city'=>'',
                'state'=>'',
                'zip'=>'',
                'phone'=>'',
                'po_box'=>''
                );
}
    
/*
$links[] = <<< EOT
<form method="POST" action="$action_url$urlparam" accept-charset="utf-8" class="display-inline">
<input type="hidden" name="address_name" value="$an" />
<input type="hidden" name="task" value="user.open_address" />
<input class="ma_btn plain-input-field" style="width: auto; cursor: pointer;" type="submit" value="Edit Address" />
</form>
EOT;
*/

$links[] = <<< EOT
<a href="$action_url$urlparam" class="ma_btn text-decoration-none plain-input-field width-auto cursor-pointer">Edit Address</a>
EOT;

/*
    if($address['id']) {
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');
$links[] = <<< EOT
<form method="POST" action="$action_url" accept-charset="utf-8" class="display-inline">
<input type="hidden" name="address_name" value="$an" />
<input type="hidden" name="task" value="user.delete_address" />
<input class="amz_red text-decoration-underline plain-input-field display-inline" style="width: auto; cursor: pointer;" type="submit" value="Delete Address" />
</form>
EOT;
    }
  
    
    if($tpt_vars['user']['data']['default_address'] == $address['id']) {
$links[] = <<< EOT
<span style="color: #909090;" class="font-style-italic">(This&nbsp;is&nbsp;Your&nbsp;Default&nbsp;Address)</span>
EOT;
    } else {
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');
$links[] = <<< EOT
<form method="POST" action="$action_url" accept-charset="utf-8" class="display-inline">
<input type="hidden" name="address_name" value="$an" />
<input type="hidden" name="task" value="user.default_address" />
<input class="amz_red text-decoration-underline plain-input-field display-inline" style="width: auto; cursor: pointer;" type="submit" value="Make This Your Default Address" />
</form>
EOT;
    }

    
    $addparam = '';
    if(!empty($_GET['shipping']) || !empty($_GET['payment'])) {
        $addparam = '?tocart=1';
    }
    
    //var_dump($tpt_vars['user']);die();
    if($tpt_vars['user']['shipping_address'] == $address['id']) {
$links[] = <<< EOT
<span style="color: #909090;" class="font-style-italic">(This&nbsp;is&nbsp;Your&nbsp;Current&nbsp;Shipping&nbsp;Address)</span>
EOT;
    } else {
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');
$links[] = <<< EOT
<form method="POST" action="$action_url$addparam" accept-charset="utf-8" class="display-inline">
<input type="hidden" name="address_name" value="$an" />
<input type="hidden" name="task" value="user.select_shipping_address" />
<input class="amz_red text-decoration-underline plain-input-field display-inline" style="width: auto; cursor: pointer;" type="submit" value="Make this Your Current Shipping Address" />
</form>
EOT;
}
    
    if($tpt_vars['user']['payment_address'] == $address['id']) {
$links[] = <<< EOT
<span style="color: #909090;" class="font-style-italic">(This&nbsp;is&nbsp;Your&nbsp;Current&nbsp;Payment&nbsp;Address)</span>
EOT;
    } else {
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');
$links[] = <<< EOT
<form method="POST" action="$action_url$addparam" accept-charset="utf-8" class="display-inline">
<input type="hidden" name="address_name" value="$an" />
<input type="hidden" name="task" value="user.select_payment_address" />
<input class="amz_red text-decoration-underline plain-input-field display-inline" style="width: auto; cursor: pointer;" type="submit" value="Make this Your Current Payment Address" />
</form>
EOT;
}
*/  
    $address_links = implode(" | ", $links);
    $address_names = implode(' ', array_filter(array($address['fname'], $address['lname'])));
    $address_company = isset($address['company']) ? $address['company'] : '';
    $address_name = !empty($address['id'])?'<span class="amz_red font-size-24 todayshop-bolditalic">Shipping Address</span>':'<span class="amz_red font-size-24 todayshop-bolditalic">Billing Address</span>';
    $address_addr = '<div class="height-22 font-size-14 white-space-nowrap" style="color: #909090;">'.implode('</div><div class="height-22">', array_filter(array($address['address1'], $address['address2']))).'</div>';
    $countries = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_countries', 'id,name,states_source', '', 'id', false);
    $address_country = isset($countries[$address['country']]['name']) ? $countries[$address['country']]['name'] : '';
    $address_state = '';
    if (isset($countries[$address['country']]['states_source']) && preg_match('#^\{.*\}$#', $countries[$address['country']]['states_source'], $mtch)) {
        $address_state = $address['state'];
    } else {
		//tpt_dump($address['country']);
		if(!empty($address['country'])) {
			$stvals = $tpt_vars['db']['handler']->getData($tpt_vars, $countries[$address['country']]['states_source'], 'id,state', '', 'id', false);
			$address_state = $stvals[$address['state']]['state'];
		}
    }
    $address_city = $address['city'];
    $address_zip = $address['zip'];
    $address_phone = $address['phone'];
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'address-box.tpt.php');
    $address_html .= $address_box;
}
$tpt_vars['template']['content'] .= <<< EOT
    <div class="overflow-hidden clearBoth">
        <div class="my-account">My Addresses</div>
        <div class="top-line-sep"></div>
            <div class="amz_login white-box clearFix padding-10">           
            <div id="addresses-container" class="clearFix position-relative width-100prc" style="background: transparent url($tpt_imagesurl/banner-my-addresses.png) no-repeat scroll center 40px;">
                $address_html
            </div>
        </div>
    </div>
EOT;
