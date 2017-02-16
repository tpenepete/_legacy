<?php

defined('TPT_INIT') or die('access denied');

// master template

$tpt_vars['template']['content'] = <<< EOT
<div class="text-align-center">
    <div class="text-align-left">
EOT;

switch(intval($tpt_vars['environment']['page_rule']['id'], 10)) {
	case 5 :
		if(!$tpt_vars['config']['https']) {
			//$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/logout');
			$return_url = REQUEST_URL_SECURE;
			tpt_request::redirect($tpt_vars, $return_url);
		} else if(!$tpt_vars['user']['isLogged']) {
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else if(strtolower($_SERVER['REQUEST_METHOD']) != 'post') {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/logout');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-account-main.tpt.php');
		}
		break;
	case 8 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav clearFix"><li class="first" title="My Account">My Account</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-account-main.tpt.php');
		}
		break;
	case 9 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Addresses">My Addresses</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-addresses.tpt.php');
		}
		break;
	case 7 :
		if(!$tpt_vars['user']['isLogged']) {
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
		} else {
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'logout-main.tpt.php');
		}
		break;
	case 10 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Addresses"><a href="/my-addresses">My Addresses</a></li><li title="Manage Address">Manage Address</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'manage-address.tpt.php');
		}
		break;
	case 12 :
		//var_dump($tpt_vars['user']['isLogged']);die();
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Account Info">My Account Info</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-account-info.tpt.php');
		}
		break;
	case 115 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Orders">My Orders</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-orders.tpt.php');
		}
		break;
	case 192 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
		} else {
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'order-summary-tpt.php');
		}
		break;
	case 267 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Orders">My Abandoned Carts</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-abandoned-carts.tpt.php');
		}
		break;
	case 214 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="ReOrder">ReOrder</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'re-order.tpt.php');
		}
		break;
	case 194 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_cssurl = TPT_CSS_URL;
			$tpt_vars['template_data']['head'][] = <<< EOT
<link type="text/css" rel="stylesheet" href="$tpt_cssurl/wide_layout.css" />
EOT;

			$orderid = intval((isset($_GET['order_id'])?$_GET['order_id']:0), 10);



			$order = $tpt_vars['db']['handler']->getData($tpt_vars, 'temp_custom_orders', '*', ' `order_id`='.$orderid.' AND `customer_id`='.$tpt_vars['user']['userid'], '', false);
			//$order = $tpt_vars['db']['handler']->getData($tpt_vars, 'temp_custom_orders', '*', ' `order_id`='.$orderid, '', false);


			if(empty($orderid) || empty($order)) {
				$checkoutbar = amz_checkout::getCheckoutBar($tpt_vars, 3);

				//$tpt_vars['template']['content'] .= $checkoutbar;

				$tpt_vars['template']['content'] .= <<< EOT
<div class="padding-top-35">
<br />
No such order!

</div>
EOT;

				//return;
			} else {
				$order = reset($order);

				$quoteid = $order['id'];

				$order_cart_row = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_order_carts', '*', ' `order_id`='.$orderid.' AND `user_id`='.intval($tpt_vars['user']['userid'], 10).' ORDER BY `id` DESC', '', false);

				if(empty($quoteid) || empty($order_cart_row)) {
					$order_products = array();
				} else {
					$order_cart_row = reset($order_cart_row);
					$order_cart = unserialize($order_cart_row['cart_pdt']);
					$order_products = $order_cart;
				}

				$second_li = '';

				$pos = strrpos((isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:''), "re-order");
				if ($pos === false) { // note: three equal signs
					$second_li = '<li title="My Orders"><a href="/my-orders">My Orders</a></li>';
				}
				else
					$second_li = '<li title="ReOrder"><a href="/re-order">ReOrder</a></li>';

				$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li>'.$second_li.'<li title="View Order">View Order</li></ul>';
				include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'view-order.tpt.php');
			}
		}
		break;
	case 118 :
		if(!$tpt_vars['user']['isLogged']) {
			$tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
		} else {
			$tpt_vars['template']['content'] .= '<ul class="article-nav"><li class="first" title="My Account"><a href="/my-account">My Account</a></li><li title="My Account Info"><a href="/my-account-info">My Account Info</a></li><li title="Change Password">Change Password</li></ul>';
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'change-password.tpt.php');
		}
		break;
	case 120 :
		if($tpt_vars['user']['isLogged']) {
			//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
			$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
			tpt_request::redirect($tpt_vars, $return_url);
		} else {
			include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password.tpt.php');
		}
		break;
	case 122 :
		$ppolicylink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/policies');
		$ppolicylink .= '#password';
		tpt_request::redirect($tpt_vars, $ppolicylink);
		//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'password-policy.tpt.php');
		break;
}
$tpt_vars['template']['content'] .= <<< EOT
    </div>
</div>
EOT;
