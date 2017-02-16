<?php

defined('TPT_INIT') or die('access denied');

class tpt_UserFunctions {
    
    function __construct(&$vars) {
    }

    function beforeContent(&$vars) {
        $users_module = getModule($vars, 'Users');

        if($vars['environment']['request_method'] == 'post') {
            $task = $_POST['task'];
            $tpt_vars = &$vars;
            //include(TPT_USERSSCRIPTS_DIR.DIRECTORY_SEPARATOR.'process_user_input.php');
            $result = $users_module->process_user_input($vars, $_POST, $task);
			//tpt_dump($result, true);
            
            if((strtolower($task) == 'user.logout') && !$vars['user']['isLogged']) {
                $return_url = $vars['environment']['logout_return_url'];
                tpt_request::redirect($vars, $return_url);
            } else if((strtolower($task) == 'user.logout2') && !$vars['user']['isLogged']) {
                $return_url = $vars['url']['handler']->wrap($vars, '/login-register');
                tpt_request::redirect($vars, $return_url);
            } else if(((strtolower($task) == 'user.add_address') || (strtolower($task) == 'user.edit_address') || (strtolower($task) == 'user.edit_shipping_address') || (strtolower($task) == 'user.edit_payment_address') || (strtolower($task) == 'user.delete_address') || (strtolower($task) == 'user.default_address') || (strtolower($task) == 'user.edit_account_info')) && $vars['template_data']['valid_form']) {
                if(!empty($_GET['fromshipping'])) {
                    $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
                } else if(!empty($_GET['frompayment'])){
                    $return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
                } else {
                    $return_url = $vars['url']['handler']->wrap($vars, '/my-addresses');
                }
                tpt_request::redirect($vars, $return_url);
            } else if((strtolower($task) == 'user.edit_account_info') && $vars['template_data']['valid_form']) {
                if(!empty($_GET['fromshipping'])) {
                    $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
                } else if(!empty($_GET['frompayment'])){
                    $return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
                } else {
                    $return_url = $vars['url']['handler']->wrap($vars, '/my-account-info');
                }
                tpt_request::redirect($vars, $return_url);
            } else if(strtolower($task) == 'user.edit_password2') {
                if(!empty($_GET['fromshipping'])) {
                    $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
                } else if(!empty($_GET['frompayment'])){
                    $return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
                } else {
                    $return_url = $vars['url']['handler']->wrap($vars, '/login-register');
                }
                tpt_request::redirect($vars, $return_url);
            } else if((strtolower($task) == 'user.same_address')) {
                $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
                tpt_request::redirect($vars, $return_url);
            } else if((strtolower($task) == 'user.select_shipping_address') || (strtolower($task) == 'user.select_payment_address')) {
                if(!empty($_GET['fromshipping'])) {
                    $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
                    tpt_request::redirect($vars, $return_url);
                }
            } else if(strtolower($task) == 'user.open_address') {
                $vars['template_data']['valid_form'] = false;
                $address_entr = false;
                $address_name = mysql_real_escape_string(base64_decode($_POST['address_name']));
                foreach($vars['user']['addresses'] as $address) {
                    if($address_name == $address['address_name']) {
                        $address_entr = $address;
                        $vars['template_data']['valid_form'] = true;
                        break;
                    }
                }
                
                if(!$vars['template_data']['valid_form']) {
                    $return_url = $vars['url']['handler']->wrap($vars, '/my-addresses');
                    tpt_request::redirect($vars, $return_url);
                }
            } else if(((strtolower($task) == 'user.login') || (strtolower($task) == 'user.edit_password')) && $vars['user']['isLogged']) {
                //die('aasdasd');
                $return_url = $vars['environment']['login_return_url'];
                //var_dump($return_url);die();
                tpt_request::redirect($vars, $return_url);
            } else if(strtolower($task) == 'user.register') {
                //die('aasdasd');
				//tpt_dump($result, true);
				if(!empty($result)) {
					$return_url = $vars['url']['handler']->wrap($vars, '/your-basket');
					//var_dump($return_url);die();
					tpt_request::redirect($vars, $return_url);
				}
            }
        }
        
        
        if(isset($_GET['same_address'])) {
            $task = 'user.same_address';
            $tpt_vars = &$vars;
            $users_module->process_user_input($vars, $_POST, $task);
            
            if(!empty($_GET['frompayment'])){
                $return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
            } else {
                $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
            }
            tpt_request::redirect($vars, $return_url);
        } else if(isset($_GET['action']) && ($_GET['action'] == 'cancel')) {
            //die($_GET['action']);
            $task = 'user.reset_password_cancel';
            $tpt_vars = &$vars;
            $users_module->process_user_input($vars, $_POST, $task);
            
            if(!empty($_GET['frompayment'])){
                $return_url = $vars['url']['handler']->wrap($vars, '/billing-details');
            } else if(!empty($_GET['fromshipping'])){
                $return_url = $vars['url']['handler']->wrap($vars, '/shipping-details');
            } else {
                $return_url = $vars['url']['handler']->wrap($vars, '/login-register');
            }
            tpt_request::redirect($vars, $return_url);
        }
        
    }
}

$tpt_vars['environment']['url_processors'][] = $tpt_vars['user']['functions'] = new tpt_UserFunctions($tpt_vars);