<?php

defined('TPT_INIT') or die('access denied');

// master template

$tpt_vars['template']['content'] = <<< EOT
<div class="text-align-center">
    <div class="text-align-left">
EOT;

switch(intval($tpt_vars['environment']['page_rule']['id'], 10)) {
    case 95 :
        if(!$tpt_vars['user']['isLogged']) {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
        } else {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-account-main.tpt.php');
        }
    break;
    case 97 :
        if(!$tpt_vars['user']['isLogged']) {
            $tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
        } else {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-account-main.tpt.php');
        }
        break;
    case 106 :
        if(!$tpt_vars['user']['isLogged']) {
            $tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
        } else {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'my-addresses.tpt.php');
        }
        break;
    case 107 :
        if(!$tpt_vars['user']['isLogged']) {
            $return_url = $vars['url']['handler']->wrap($vars, '/login-register');
            tpt_request::redirect($vars, $return_url);
        } else {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'logout-main.tpt.php');
        }
        break;
    case 109 :
        if(!$tpt_vars['user']['isLogged']) {
            $tpt_vars['environment']['ajax_result']['messages'][] = array('This section requires user login.', 'error');
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'login-register.tpt.php');
        } else {
            include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'manage-address.tpt.php');
        }
        break;    
}
$tpt_vars['template']['content'] .= <<< EOT
    </div>
</div>
EOT;

?>
