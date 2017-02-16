<?php

defined('TPT_INIT') or die('access denied');

//$users_module = getModule($tpt_vars, "Users");
$users_table = 'tpt_module_users';
//echo '<pre>';
//var_dump($_SESSION);
//echo '</pre>';

/*if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
	ob_start();
	var_dump($_SESSION['templay']);
	file_put_contents('session_debug.txt', ob_get_contents(), FILE_APPEND);
	ob_end_clean();
}*/
$tpt_vars['user']['username'] = (isset($_SESSION['templay']['username'])?$_SESSION['templay']['username']:'');
$tpt_vars['user']['hashid'] = (isset($_SESSION['templay']['user_id'])?$_SESSION['templay']['user_id']:0);
$tpt_vars['user']['litime'] = (isset($_SESSION['templay']['last_login'])?$_SESSION['templay']['last_login']:0);
$tpt_vars['session']['user_session']['sessionid'] = (isset($_SESSION['templay']['sessionid'])?$_SESSION['templay']['sessionid']:'');
if(!empty($_SESSION['templay']['login_return_url'])) {
	$tpt_vars['environment']['real_login_return_url'] = $_SESSION['templay']['login_return_url'];
} else {
	$tpt_vars['environment']['real_login_return_url'] = '';
}

if(!empty($_SESSION['templay']['logout_return_url'])) {
	$tpt_vars['environment']['real_logout_return_url'] = $_SESSION['templay']['logout_return_url'];
} else {
	$tpt_vars['environment']['real_logout_return_url'] = '';
}

if(!empty($_SESSION['templay']['future_back_url'])) {
	$tpt_vars['environment']['real_go_back_url'] = $_SESSION['templay']['future_back_url'];
} else {
	$tpt_vars['environment']['real_go_back_url'] = '';
}
//var_dump($tpt_vars['environment']['logout_return_url']);die();
if(!empty($_SESSION['templay']['messages'])) {
	$tpt_vars['environment']['ajax_result']['messages'] = $_SESSION['templay']['messages'];
}

if(!empty($_SESSION['templay']['execute_onload'])) {
	$tpt_vars['environment']['ajax_result']['execute_onload'] = $_SESSION['templay']['execute_onload'];
}

if (!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_session'])) {
	tpt_logger::log_session($tpt_vars, 'tpt_request_rq_session', $tpt_vars['user']['username'], $tpt_vars['user']['hashid'], $tpt_vars['user']['litime'], $tpt_vars['session']['user_session']['sessionid'], session_id());
}
if (isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_session_dev'])) {
        tpt_logger::log_session($tpt_vars, 'tpt_request_rq_session_dev', $tpt_vars['user']['username'], $tpt_vars['user']['hashid'], $tpt_vars['user']['litime'], $tpt_vars['session']['user_session']['sessionid'], session_id());
}

//var_dump($tpt_vars['user']['username']);
//var_dump($tpt_vars['user']['hashid']);
//var_dump($tpt_vars['user']['litime']);die();

//var_dump($sess->get('user_id', '', 'templay'));//die();
//tpt_dump($tpt_vars['user']['username']);//die();
//tpt_dump($tpt_vars['user']['hashid']);//die();
//tpt_dump($tpt_vars['user']['litime']);//die();
//tpt_dump($tpt_vars['session']['user_session']['sessionid']);//die();

//unset($_SESSION['templay']['mar6a']);
//$_SESSION['templay']['mar6a'] = 'aasd';
$sid = '';
$dec = '';
$uSessionFail = false;
if(!empty($tpt_vars['session']['user_session']['sessionid'])) {
    //$sid = sha1($tpt_vars['user']['litime'].encode_string($tpt_vars['user']['username'], $tpt_vars['config']['key']));
    $sid = sha1(encode_string($tpt_vars['user']['username'], $tpt_vars['config']['key']));
    //$tpt_vars['session']['user_session']['sessionid'] = $tpt_vars['session']['user_session']['sessionid'];
    $lowername = strtolower($tpt_vars['user']['username']);
    $userdata = $tpt_vars['db']['handler']->getData($tpt_vars, $users_table, '*', '`username` = "'.$lowername.'"', 'username', false );

    if(
        //empty($tpt_vars['session']['user_session']['sessionid']) ||
        /* temp fix */
        /*
        !empty($tpt_vars['user']['username']) &&
        !empty($tpt_vars['user']['litime']) &&
        (
        */
            empty($tpt_vars['user']['hashid']) ||
            !isset($tpt_vars['data']['tpt_session']['id'][$sid]) ||
            empty($userdata) ||
            ($sid !== $tpt_vars['session']['user_session']['sessionid'])
        /*
        )
        */
    ) {
        $uSessionFail = true;
    } else {
        $tpt_vars['user']['isLogged'] = true;

        //if(!$tpt_vars['config']['https']) {
        //    //$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
        //    $return_url = REQUEST_URL_SECURE;
        //    tpt_request::redirect($tpt_vars, $return_url);
        //}

        $userdata = reset($userdata);
        $tpt_vars['user']['userid'] = $userdata['id'];
        $tpt_vars['user']['data'] = $userdata;


        $tpt_vars['user']['addresses']['payment'] = array(
                                                  'id'=>0,
                                                  'address_name'=>'payment',
                                                  'title'=>$tpt_vars['user']['data']['title'],
                                                  'fname'=>$tpt_vars['user']['data']['fname'],
                                                  'mname'=>$tpt_vars['user']['data']['mname'],
                                                  'lname'=>$tpt_vars['user']['data']['lname'],
                                                  'company'=>$tpt_vars['user']['data']['company'],
                                                  'address1'=>$tpt_vars['user']['data']['address1'],
                                                  'address2'=>$tpt_vars['user']['data']['address2'],
                                                  'address3'=>$tpt_vars['user']['data']['address3'],
                                                  'country'=>$tpt_vars['user']['data']['country'],
                                                  'city'=>$tpt_vars['user']['data']['city'],
                                                  'state'=>$tpt_vars['user']['data']['state'],
                                                  'zip'=>$tpt_vars['user']['data']['zip'],
                                                  'phone'=>$tpt_vars['user']['data']['phone'],
                                                  'po_box'=>$tpt_vars['user']['data']['po_box']
                                                  );
        if($tpt_vars['user']['data']['same_address']) {
            $tpt_vars['user']['addresses']['shipping'] = $tpt_vars['user']['addresses']['payment'];
            $tpt_vars['user']['addresses']['shipping']['id'] = 1;
            $tpt_vars['user']['addresses']['shipping']['address_name'] = 'shipping';
        } else {
            //var_dump($tpt_vars['user']['data']['shipping_fname']);die();
        $tpt_vars['user']['addresses']['shipping'] = array(
                                                  'id'=>1,
                                                  'address_name'=>'shipping',
                                                  'title'=>$tpt_vars['user']['data']['shipping_title'],
                                                  'fname'=>$tpt_vars['user']['data']['shipping_fname'],
                                                  'mname'=>$tpt_vars['user']['data']['shipping_mname'],
                                                  'lname'=>$tpt_vars['user']['data']['shipping_lname'],
                                                  'company'=>$tpt_vars['user']['data']['shipping_company'],
                                                  'address1'=>$tpt_vars['user']['data']['shipping_address1'],
                                                  'address2'=>$tpt_vars['user']['data']['shipping_address2'],
                                                  'address3'=>$tpt_vars['user']['data']['shipping_address3'],
                                                  'country'=>$tpt_vars['user']['data']['shipping_country'],
                                                  'city'=>$tpt_vars['user']['data']['shipping_city'],
                                                  'state'=>$tpt_vars['user']['data']['shipping_state'],
                                                  'zip'=>$tpt_vars['user']['data']['shipping_zip'],
                                                  'phone'=>$tpt_vars['user']['data']['shipping_phone'],
                                                  'po_box'=>$tpt_vars['user']['data']['shipping_po_box']
                                                  );
        }
        $tpt_vars['user']['addresses']['shipping_data'] = array(
                                                  'id'=>2,
                                                  'address_name'=>'shipping',
                                                  'title'=>$tpt_vars['user']['data']['shipping_title'],
                                                  'fname'=>$tpt_vars['user']['data']['shipping_fname'],
                                                  'mname'=>$tpt_vars['user']['data']['shipping_mname'],
                                                  'lname'=>$tpt_vars['user']['data']['shipping_lname'],
                                                  'company'=>$tpt_vars['user']['data']['shipping_company'],
                                                  'address1'=>$tpt_vars['user']['data']['shipping_address1'],
                                                  'address2'=>$tpt_vars['user']['data']['shipping_address2'],
                                                  'address3'=>$tpt_vars['user']['data']['shipping_address3'],
                                                  'country'=>$tpt_vars['user']['data']['shipping_country'],
                                                  'city'=>$tpt_vars['user']['data']['shipping_city'],
                                                  'state'=>$tpt_vars['user']['data']['shipping_state'],
                                                  'zip'=>$tpt_vars['user']['data']['shipping_zip'],
                                                  'phone'=>$tpt_vars['user']['data']['shipping_phone'],
                                                  'po_box'=>$tpt_vars['user']['data']['shipping_po_box']
                                                  );
        /*
        $tpt_vars['user']['addresses'][0] = array(
                                                  'id'=>0,
                                                  'address_name'=>'',
                                                  'title'=>$tpt_vars['user']['data']['title'],
                                                  'fname'=>$tpt_vars['user']['data']['fname'],
                                                  'mname'=>$tpt_vars['user']['data']['mname'],
                                                  'lname'=>$tpt_vars['user']['data']['lname'],
                                                  'address1'=>$tpt_vars['user']['data']['address1'],
                                                  'address2'=>$tpt_vars['user']['data']['address2'],
                                                  'address3'=>$tpt_vars['user']['data']['address3'],
                                                  'country'=>$tpt_vars['user']['data']['country'],
                                                  'city'=>$tpt_vars['user']['data']['city'],
                                                  'state'=>$tpt_vars['user']['data']['state'],
                                                  'zip'=>$tpt_vars['user']['data']['zip'],
                                                  'phone'=>$tpt_vars['user']['data']['phone'],
                                                  'po_box'=>$tpt_vars['user']['data']['po_box']
                                                  );
        $tpt_vars['user']['addresses'] = $tpt_vars['user']['addresses'] + $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_users_addresses', '*', 'userid='.$tpt_vars['user']['data']['id'], 'id', false);


        $tpt_vars['user']['shipping_address'] = $tpt_vars['data']['tpt_session']['id'][$sid]['shipping_address'];
        $tpt_vars['user']['payment_address'] = $tpt_vars['data']['tpt_session']['id'][$sid]['payment_address'];
        */
        $tpt_vars['session']['user_session']['session'] = $tpt_vars['data']['tpt_session']['id'][$tpt_vars['session']['user_session']['sessionid']];

        $query = 'UPDATE `tpt_session` SET `lastrequest_time`='.$tpt_vars['user']['lrtime'].', `client_ip`="'.$tpt_vars['user']['client_ip'].'" WHERE `id`="'.$sid.'"';
        $tpt_vars['db']['handler']->query($query, __FILE__);
    }
}


if($uSessionFail) {
    $emptyhashid = empty($tpt_vars['user']['hashid'])?1:0;
    $emptydatarow = !isset($tpt_vars['data']['tpt_session']['id'][$sid])?1:0;
    $emptyuserdata = empty($userdata)?1:0;
    $sidmismatch = ($sid !== $tpt_vars['session']['user_session']['sessionid'])?1:0;
    $postdata = serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
    $liid = 0;
    if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_user_logout'])) {
        //die($query);
        $liid = tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout", $postdata, 'uSessionFail'.isDev(), $sid, serialize(array(!empty($tpt_vars['user'])?$tpt_vars['user']:'')).'', serialize(array(!empty($tpt_vars['data']['tpt_session']['id'][$sid])?$tpt_vars['data']['tpt_session']['id'][$sid]:'')).'', serialize(array(!empty($userdata)?$userdata:'')).'', $tpt_vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $emptyhashid.'', $emptydatarow.'', $emptyuserdata.'', $sidmismatch.'', $tpt_vars['user']['litime'], $tpt_vars['user']['username'], $tpt_vars['config']['key'], encode_string($tpt_vars['user']['username']), session_id());
    }
    if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_user_logout_dev'])) {
        //$postdata = serialize(debug_backtrace());
        $liid = tpt_logger::log_logout($tpt_vars, "tpt_request_rq_user_logout_dev", $postdata, 'uSessionFail'.isDev(), $sid, serialize(array(!empty($tpt_vars['user'])?$tpt_vars['user']:'')).'', serialize(array(!empty($tpt_vars['data']['tpt_session']['id'][$sid])?$tpt_vars['data']['tpt_session']['id'][$sid]:'')).'', serialize(array(!empty($userdata)?$userdata:'')).'', $tpt_vars['session']['user_session']['sessionid'], serialize(array(!empty($_SESSION['templay'])?$_SESSION['templay']:'')), $emptyhashid.'', $emptydatarow.'', $emptyuserdata.'', $sidmismatch.'', $tpt_vars['user']['litime'], $tpt_vars['user']['username'], $tpt_vars['config']['key'], encode_string($tpt_vars['user']['username']), session_id());
    }
	/*
    if(isUltraUser()) {
        $tpt_vars['environment']['ajax_result']['messages'][] = array('Session fail. ID:'.$liid.'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG1:'.$sid.'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG2:'.(empty($userdata)).'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG3:'.$tpt_vars['session']['user_session']['sessionid'].'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG4:'.$emptyhashid.'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG5:'.$emptydatarow.'', 'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('DEBUG6:'.$sidmismatch.'', 'error');
    }
	*/

    //var_dump('asdasdasdsad');
    //$query = 'DELETE FROM `tpt_session` WHERE `userid`='.$tpt_vars['user']['userid'];
    //$tpt_vars['db']['handler']->query($query, __FILE__);

    $tpt_vars['user']['username'] = '';
    $tpt_vars['user']['userid'] = 0;
    $tpt_vars['user']['data'] = array('id'=>0, 'usertype'=>1);
    $tpt_vars['user']['hashid'] = 0;
    $tpt_vars['user']['litime'] = 0;
    $tpt_vars['session']['user_session']['sessionid'] = '';
    $tpt_vars['session']['user_session']['session'] = array();

	unset($_SESSION['templay']['user_id']);
	unset($_SESSION['templay']['username']);
	unset($_SESSION['templay']['sessionid']);
	unset($_SESSION['templay']['last_login']);

}

