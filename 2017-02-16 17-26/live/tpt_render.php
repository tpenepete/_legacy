<?php

defined('TPT_INIT') or die('access denied');


$template_module = getModule($tpt_vars, 'Template');
$users_module = getModule($tpt_vars, 'Users');
$users_table = $users_module->moduleTable;


///////////// END INCLUDE PAGE SCRIPTS OR 404.php */

// include js according to the page ////
if (isset($tpt_vars['environment']['page_rule']['id'])
	&& is_file($dfl = dirname(__FILE__) . '/js/dyn/' . $tpt_vars['environment']['page_rule']['id'] . '.js')
) {
	$tpt_vars['template_data']['head'][] = '<script type="text/javascript" src="'
		. $tpt_baseurl . '/js/dyn/' . $tpt_vars['environment']['page_rule']['id']
		. '.js"></script>';
}

//////////////////////////////////////


//************ ADMIN SECTION

//var_dump($tpt_vars['template_data']['tpt_logged_in']);die();
tpt_logger::dump($tpt_vars, $tpt_vars['environment']['isAdmin'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$tpt_vars[\'environment\'][\'isAdmin\']', __FILE__.' '.__LINE__);
$uid = $users_module->get_user_id_from_cookie($tpt_vars, (!empty($_COOKIE['tpt_logged_user'])?$_COOKIE['tpt_logged_user']:''));
tpt_logger::dump($tpt_vars, (!empty($_COOKIE['tpt_logged_user'])?$_COOKIE['tpt_logged_user']:'').' '.$uid, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$_COOKIE[\'tpt_logged_user\'].\' \'.$uid', __FILE__.' '.__LINE__);
//tpt_dump('asdadsasdasd', true);
//tpt_dump($tpt_vars['user']['isLogged'], true);
if(!empty($tpt_vars['template_data']['tpt_logged_in']) && !$tpt_vars['user']['isLogged']) {
	//if($tpt_vars['environment']['isAdmin']) {
	//$tpt_vars['environment']['ajax_result']['messages']['SESSION_EXPIRED'] = array('text'=>'Your session has expired. Please login.', 'type'=>'notice');
	//}


	/*
	if(isUltraUser()) {
		$tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'DEBUG: Your session has expired. Please login.', 'type'=>'notice');
	}
	*/

	if(!empty($uid) && is_int($uid)) {

		$query = <<< EOT
        SELECT * FROM `$users_table` WHERE `id`=$uid
EOT;
		$tpt_vars['db']['handler']->query($query);
		$userdata = $tpt_vars['db']['handler']->fetch_assoc();
		if(!empty($userdata['last_cart_id'])) {
			$cart_id = $userdata['last_cart_id'];
			$query = <<< EOT
        INSERT INTO `tpt_users_lost_carts` (`user_id`, `cart_id`) VALUES($uid, $cart_id)
EOT;
			$tpt_vars['db']['handler']->query($query);
			$users_module->set_abandoned_cart_notification($tpt_vars, $uid, $state=1);
			tpt_current_user::update_store_cart_id($tpt_vars, 0);
			//$tpt_vars['environment']['ajax_result']['messages'][] = array('text'=>'An abandoned cart has been stored in your account history.', 'type'=>'notice');
		}
	}
}

////////////// END ADMIN SECTION */


//************ PREP AND RENDER TEMPLATE
$js_ajaxurl = $tpt_baseurl;
if(!$tpt_vars['environment']['isAdministration']) {
	//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_head_front.php');
	tpt_template::getFrontendHeadContent($tpt_vars);
} else {
	$js_ajaxurl = $tpt_admin_baseurl;
}



$js = <<< EOT
<script type="text/javascript">
//<![CDATA[
var base_url = '$tpt_baseurl';
var ajax_url = '$js_ajaxurl';
var resource_url = '$tpt_resourceurl';
var tpt_images_url = '$tpt_imagesurl';
var res_url = '$tpt_resurl';
//]]>
</script>
EOT;
array_unshift($tpt_vars['template_data']['head'], $js);





$hhhhh = '';
if ($_SERVER['REMOTE_ADDR']=='85.130.3.155') {
	ob_start();
	var_dump('sssss');
	$hhhhh = ob_get_clean();
//	$vars['template_data']['head'][] = $hhhhh;
//	$vars['template_data']['head'][] = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
}


/*
$con_top = $tpt_vars['template']['con_top'];
$con_bottom = $tpt_vars['template']['con_bottom'];
*/
$subpath = $tpt_vars['config']['subpath'];



if(!empty($_GET['asyncload'])) {
	die($content);
}

//tpt_dump($tpt_vars['modules']['handler'], true);die();
/*
if(($_SERVER['REMOTE_ADDR'] == '109.160.0.218') && ($_GET['debug'] == 'debu')) {
    //var_dump($this->pricingTable);//die();
    //var_dump($this->total_qty);//die();
    //var_dump(self::$pricing_data);//die();
    //var_dump($this->mfgcost);
    die('eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee');

}
*/

$tpt_vars['admin']['handler']->after_content($tpt_vars);
//$admin_tabs = json_encode($tpt_vars['admin']['template_data']['admin_tabs']);
//var_dump($tpt_vars['template']['admin_content']);
//tpt_dump('asd');
//tpt_dump($tpt_vars['user']['isLogged'], true);
$template = $template_module->load($tpt_vars);
$template_module->render($tpt_vars, $template);

//tpt_dump('asd');
//tpt_dump(__LINE__, true);
////////////// END PREP AND RENDER TEMPLATE */
