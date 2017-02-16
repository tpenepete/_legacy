<?php

defined('TPT_INIT') or die('access denied');
/*if (!function_exists('apache_request_headers')) {
    function apache_request_headers() {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', strtolower($arh_key));
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        if (isset($_SERVER['CONTENT_LENGTH'])) $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        return ($arh);
    }
}*/


class tpt_logger {

    static $dump_content = array();

    function __construct(&$vars) {
    }

    static function log_paypal_response(&$vars, $file, $rq, $rsp, $table) {
		$pplogdb = DB_DB;

        $columns = $vars['db']['handler']->show_columns($table);
        $timestamp1 = $vars['environment']['request_time'];

        $rsp['file'] = $file;
        $rsp['rq'] = $rq;
        $invnum = !empty($rsp['PAYMENTREQUEST_0_INVNUM'])?$rsp['PAYMENTREQUEST_0_INVNUM']:0;
        $rsp['data'] = http_build_query($rsp);
        $txprm = array_intersect_key($rsp, $columns);
        $txprmfld = '';
        if(!empty($txprm)) {
            $txprmfld = '`'.implode('`, `', array_keys($txprm)).'`';
        }
        $txprmval = '';
        if(!empty($txprm)) {
            array_map('mysql_real_escape_string', $txprm);
            $txprmval = '"'.implode('", "', $txprm).'"';
        }
        $ip = $vars['user']['client_ip'];
        $user = intval($vars['user']['userid'], 10);

        /*
        $query = <<< EOT
INSERT INTO `$table`
(
    `timestamp1`,
    `ip`,
    `user`,
    `INVNUM`,
    $txprmfld
) VALUES
(
    $timestamp1,
    "$ip",
    $user,
    "$invnum",
    $txprmval
)
EOT;
        */
        $query = <<< EOT
INSERT INTO `$pplogdb`.`$table`
(
    `timestamp1`,
    `ip`,
    `user`,
    $txprmfld
) VALUES
(
    $timestamp1,
    "$ip",
    $user,
    $txprmval
)
EOT;
        //tpt_dump($columns);
        //tpt_dump($details);
        //tpt_dump($txprm);
        //tpt_dump($query);
        $vars['db']['handler']->query($query, __FILE__);
    }

    static function log_paypal_response_setexpresscheckout(&$vars, $file, $rq, $rsp) {
        self::log_paypal_response($vars, $file, $rq, $rsp, 'tpt_pgrq_ppp_app_response_setexpresscheckout');
    }


    static function log_paypal_response_getexpresscheckoutdetails(&$vars, $file, $rq, $rsp) {
        self::log_paypal_response($vars, $file, $rq, $rsp, 'tpt_pgrq_ppp_app_response_getexpresscheckoutdetails');
    }


    static function log_paypal_response_doexpresscheckoutpayment(&$vars, $file, $rq, $rsp) {
        self::log_paypal_response($vars, $file, $rq, $rsp, 'tpt_pgrq_ppp_app_response_doexpresscheckoutpayment');
    }

    static function log_common(&$vars, $table, $source, $data) {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $backtrace_export = var_export($backtrace, true);
		ob_start();
		debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$backtrace_print = ob_get_clean();
		$_b = $backtrace;
		array_shift($_b);
		$cls = !empty($_b[0]['class'])?$_b[0]['class']:'';
		$fn = !empty($_b[0]['function'])?$_b[0]['function']:'';
		$file = !empty($_b[0]['file'])?$_b[0]['file']:'';
		$line = !empty($_b[0]['line'])?$_b[0]['line']:'';
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

		$data_str = '';
		if(is_array($data)) {
			foreach($data as $key=>$value) {
				ob_start();
				var_dump($value);
				$value = ob_get_clean();
				$data_str .= <<< EOT
=================================================================================================================
$key
-----------------------------------------------------------------------------------------------------------------
$value



=================================================================================================================
EOT;

			}

		} else {
			$data_str = $data;
		}

        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `source`,
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `file`,
            `data`,
            `user_agent`,
            `referrer`,
            `stack`,
            `stack_print`,
            `phpsessionid`
        ) VALUES
        (
            "'.mysql_real_escape_string($source).'",
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($file).'",
            "'.mysql_real_escape_string($data_str).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($backtrace_export).'",
            "'.mysql_real_escape_string($backtrace_print).'",
            "'.mysql_real_escape_string(session_id()).'"
        )';
        //die($query);
        $vars['db']['handler']->query($query);
    }


    static function log_memory_usage(&$vars) {
		//tpt_dump('asd');
		$query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`tpt_request_rq_memory_usage`
        (
            `request_id`,
            `request_table`,
            `before_init`,
            `after_init`,
            `before_content_processors_includes`,
            `after_content_processors_includes`,
            `before_before_content_processors`,
            `after_before_content_processors`,
            `before_main_include`,
            `after_main_include`,
            `before_after_content_processors`,
            `after_after_content_processors`,
            `memory_limit`
        ) VALUES
        (
            '.intval($vars['stats']['request_id']['id'], 10).',
            "'.mysql_real_escape_string($vars['stats']['request_id']['table']).'",
            '.intval($vars['stats']['memory_usage']['before_init'], 10).',
            '.intval($vars['stats']['memory_usage']['after_init'], 10).',
            '.intval($vars['stats']['memory_usage']['before_content_processors_includes'], 10).',
            '.intval($vars['stats']['memory_usage']['after_content_processors_includes'], 10).',
            '.intval($vars['stats']['memory_usage']['before_content_processors_includes'], 10).',
            '.intval($vars['stats']['memory_usage']['after_content_processors_includes'], 10).',
            '.intval($vars['stats']['memory_usage']['before_main_include'], 10).',
            '.intval($vars['stats']['memory_usage']['after_main_include'], 10).',
            '.intval($vars['stats']['memory_usage']['before_after_content_processors'], 10).',
            '.intval($vars['stats']['memory_usage']['after_after_content_processors'], 10).',
            '.intval($vars['stats']['memory_limit'], 10).'
        )';
		//die($query);
		$vars['db']['handler']->query($query);
	}

    static function log_request(&$vars, $table, $data, $phpsessionid='') {
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

		$headers = apache_request_headers();
		$headers = var_export($headers, true);
		$response_headers = apache_response_headers();
		$response_headers = var_export($response_headers, true);
		$server = var_export($_SERVER, true);

        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `phpsessionid`,
            `headers`,
            `response_headers`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($phpsessionid).'",
            "'.mysql_real_escape_string($headers).'",
            "'.mysql_real_escape_string($response_headers).'"
        )';
                    //die($query);
        $vars['db']['handler']->query($query);
		$request_id = $vars['db']['handler']->last_id();

		return array(
			'id'=>$request_id,
			'table'=>$table,
		);
    }
    static function log_blog(&$vars, $table, $data, $phpsessionid='') {
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

		$headers = apache_request_headers();
		$headers = var_export($headers, true);
		$response_headers = apache_response_headers();
		$response_headers = var_export($response_headers, true);
		$server = var_export($_SERVER, true);

        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `phpsessionid`,
            `headers`,
            `response_headers`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($phpsessionid).'",
            "'.mysql_real_escape_string($headers).'",
            "'.mysql_real_escape_string($response_headers).'"
        )';
                    //die($query);
        $vars['db']['handler']->query($query);
		$request_id = $vars['db']['handler']->last_id();

		return array(
			'id'=>$request_id,
			'table'=>$table,
		);
    }

    static function log_cc_payment(&$vars, $table, $log_post_string, $post_response, $state, $file) {
        //$data = file_get_contents("php://input");
		$data = tpt_security::encode_string2($vars, file_get_contents("php://input"));
        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `payment_method`,
            `shipping_method`,
            `state`,
            `file`,
            `log_post_string`,
            `post_response`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string(amz_checkout::$payment_method).'",
            "'.mysql_real_escape_string(amz_checkout::$shipping_method).'",
            "'.mysql_real_escape_string($state).'",
            "'.mysql_real_escape_string($file).'",
            "'.mysql_real_escape_string($log_post_string).'",
            "'.mysql_real_escape_string($post_response).'")';
        //die($query);
        $vars['db']['handler']->query($query);
        $log_insert_id = $vars['db']['handler']->last_id();
        return $log_insert_id;
    }

    static function log_redirect(&$vars, $table, $log_redirect, $log_url) {
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `user_agent`,
            `referrer`,
            `log_redirect`,
            `redirect_url`
        ) VALUES (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            '.$log_redirect.',
            "'.mysql_real_escape_string($log_url).'")';
        //die($query);
        $vars['db']['handler']->query($query);
    }


    static function log_query_error(&$vars, $table, $query, $file, $line, $error = '') {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$backtrace = var_export($backtrace, true);
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');
        $errorString = is_array($error) ? implode(' ## ', $error) : $error;

        $qry = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `file`,
            `line`,
            `data`,
            `backtrace`,
            `user_agent`,
            `referrer`,
            `error`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($file).'",
            "'.mysql_real_escape_string($line).'",
            "'.mysql_real_escape_string($query).'",
            "'.mysql_real_escape_string($backtrace).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($errorString).'")';
        //die($query);
        //$vars['db']['handler']->query('asd');
        $vars['db']['handler']->query($qry);
    }
    static function log_endless_loop(&$vars, $table, $query, $file, $line) {
		$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$backtrace = var_export($backtrace, true);
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

        $qry = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `file`,
            `line`,
            `data`,
            `backtrace`,
            `user_agent`,
            `referrer`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($file).'",
            "'.mysql_real_escape_string($line).'",
            "'.mysql_real_escape_string($query).'",
            "'.mysql_real_escape_string($backtrace).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'")';
        //die($query);
        //$vars['db']['handler']->query('asd');
        $vars['db']['handler']->query($qry);
    }

    static function log_order_status_change(&$vars, $table, $df, $order_ids, $tcoquery, $query) {
        $qry = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `df`,
            `order_ids`,
            `tcoquery`,
            `query`,
            `user_agent`,
            `referrer`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($df).'",
            "'.mysql_real_escape_string($order_ids).'",
            "'.mysql_real_escape_string($tcoquery).'",
            "'.mysql_real_escape_string($query).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'")';
        //die($query);
        $vars['db']['handler']->query($qry);
    }


    static function log_iorder(&$vars, $table, $state, $file) {
	$postdata = file_get_contents("php://input");

	$query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `payment_method`,
            `shipping_method`,
            `state`,
            `file`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($postdata).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string(amz_checkout::$payment_method).'",
            "'.mysql_real_escape_string(amz_checkout::$shipping_method).'",
            "'.$state.'",
            "'.mysql_real_escape_string($file).'"
        )';
	//die($query);
	$vars['db']['handler']->query($query);
	$log_insert_id = $vars['db']['handler']->last_id();
        //die();
        //tpt_dump($log_insert_id, true);
        return $log_insert_id;

    }



    static function log_session(&$vars, $table, $username, $hashid, $litime, $sessionid, $phpsessionid='') {

	$query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `session1`,
            `session2`,
            `session3`,
            `session4`,
            `phpsessionid`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string(serialize($_SESSION)).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($username).'",
            "'.mysql_real_escape_string($hashid).'",
            "'.mysql_real_escape_string($litime).'",
            "'.mysql_real_escape_string($sessionid).'",
            "'.mysql_real_escape_string($phpsessionid).'"
        )';
	//die($query);
	$vars['db']['handler']->query($query);
	//$log_insert_id = $vars['db']['handler']->last_id();
        //return $log_insert_id;

    }




    static function log_send_paypal_invoice(&$vars, $table, $data, $orderid) {
		$tptlogsdb = DB_DB_TPT_LOGS;

        $userip = $vars['user']['client_ip'];
        $userid = $vars['user']['userid'];
        $time = $vars['environment']['request_time'];
        $method = $vars['environment']['request_method'];

        $query = <<< EOT
INSERT INTO `$tptlogsdb`.`$table`
(
    `timestamp`,
    `ip`,
    `userid`,
    `request_method`,
    `to`,
    `orderid`
)
VALUES (
    $time,
    "$userip",
    $userid,
    "$method",
    "$data",
    "$orderid"
)
EOT;
        //tpt_dump($query, true);
        $vars['db']['handler']->query($query);
        $iid = $vars['db']['handler']->last_id();
        return $iid;
    }




    static function log_cart(&$vars, $table, $task='unspecified', $products, $totals, $builder_id=-100) {
		$tptlogsdb = DB_DB_TPT_LOGS;

        $userip = $vars['user']['client_ip'];
        $userid = $vars['user']['userid'];
        $time = $vars['environment']['request_time'];

        $products = mysql_real_escape_string(serialize($products));

        $totals = mysql_real_escape_string(serialize($totals));

        $builder_id = intval($builder_id, 10);

        $query = <<< EOT
INSERT INTO `$tptlogsdb`.`$table`
(
    `ip`,
    `userid`,
    `timestamp`,
    `action`,
    `products`,
    `builder`,
    `cart_totals`
)
VALUES (
    "$userip",
    $userid,
    $time,
    "$task",
    "$products",
    $builder_id,
    "$totals"
)
EOT;
        //tpt_dump($query, true);
        $vars['db']['handler']->query($query);
        $iid = $vars['db']['handler']->last_id();
        return $iid;
    }




    static function log_cart2(&$vars, $table, $task='unspecified', $builder_id=-100) {
		$tptlogsdb = DB_DB_TPT_LOGS;

        $userip = $vars['user']['client_ip'];
        $userid = $vars['user']['userid'];
        $time = $vars['environment']['request_time'];

        $products = mysql_real_escape_string(serialize(amz_cart::$products));

        $totals = mysql_real_escape_string(serialize(amz_cart::$totals));

        $builder_id = intval($builder_id, 10);

        $query = <<< EOT
INSERT INTO `$tptlogsdb`.`$table`
(
    `ip`,
    `userid`,
    `timestamp`,
    `action`,
    `products`,
    `builder`,
    `cart_totals`
)
VALUES (
    "$userip",
    $userid,
    $time,
    "$task",
    "$products",
    $builder_id,
    "$totals"
)
EOT;
        //var_dump($query);die();
        $vars['db']['handler']->query($query);
        $iid = $vars['db']['handler']->last_id();
        return $iid;
    }




    static function log_pricing(&$vars, $table, $task='unspecified_p', $bulder_id='-200', $products=array(), $totals=array()) {
		$tptlogsdb = DB_DB_TPT_LOGS;

        $userip = $vars['user']['client_ip'];
        $userid = $vars['user']['userid'];
        $time = $vars['environment']['request_time'];

        $products = mysql_real_escape_string(serialize($products));

        $totals = mysql_real_escape_string(serialize($totals));

        $query = <<< EOT
INSERT INTO `$tptlogsdb`.`$table`
(
    `ip`,
    `userid`,
    `timestamp`,
    `products`,
    `pricing`,
    `builder`
)
VALUES (
    "$userip",
    $userid,
    $time,
    "$products",
    "$totals",
    $bulder_id
)
EOT;
        //var_dump($query);die();
        //tpt_dump($query, true);
        $vars['db']['handler']->query($query);
        $iid = $vars['db']['handler']->last_id();
        return $iid;
    }





    static function log_ordersearch(&$vars, $table, $data, $state) {

	$query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `payment_method`,
            `shipping_method`,
            `state`,
            `file`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string(amz_checkout::$payment_method).'",
            "'.mysql_real_escape_string(amz_checkout::$shipping_method).'",
            "'.$state.'",
            "'.mysql_real_escape_string(__FILE__).'"
        )';
	//die($query);
	$vars['db']['handler']->query($query);
	//$log_insert_id = $vars['db']['handler']->last_id();
        //return $log_insert_id;

    }





    static function log_curl(&$vars, $table, $rq_body, $rq_method, $resetcookie, $hdr, $htmlinfo, $xxx, $errno, $error) {
		$rq_body = tpt_security::encode_string2($vars, $rq_body);
		$date = date('Y-m-d H:i:s', $vars['environment']['request_time']);
		$timezone = date('O');

        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `date_time`,
            `timezone`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `user_agent`,
            `referrer`,
            `rq_body`,
            `rq_method`,
            `resetcookie`,
            `rq_headers`,
            `rqrsp_info`,
            `rsp_response`,
            `rsp_errno`,
            `rsp_error`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$date.'",
            "'.$timezone.'",
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($rq_body).'",
            "'.mysql_real_escape_string($rq_method).'",
            "'.intval($resetcookie, 10).'",
            "'.mysql_real_escape_string($hdr).'",
            "'.mysql_real_escape_string($htmlinfo).'",
            "'.mysql_real_escape_string($xxx).'",
            "'.mysql_real_escape_string($errno).'",
            "'.mysql_real_escape_string($error).'"
        )';
                    //die($query);
        //if($table == "tpt_request_rq_curl_dev")
        //tpt_dump($query, true);
        $vars['db']['handler']->query($query);
    }





    static function log_logout(&$vars, $table, $data, $state='unspecified', $sid='', $hashid='', $sessiondata='', $userdata='', $usersessionid='', $session='', $emptyhashid='', $emptydatarow='', $emptyuserdata='', $sidmismatch='', $litime=0, $username='', $cfk='', $enc='', $phpsessionid='') {
        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `state`,
            `sid`,
            `hashid`,
            `sessiondata`,
            `userdata`,
            `usersessionid`,
            `session`,
            `emptyhashid`,
            `emptydatarow`,
            `emptyuserdata`,
            `sidmismatch`,
            `litime`,
            `username`,
            `cfk`,
            `enc`,
            `phpsessionid`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($state).'",
            "'.mysql_real_escape_string($sid).'",
            "'.mysql_real_escape_string($hashid).'",
            "'.mysql_real_escape_string($sessiondata).'",
            "'.mysql_real_escape_string($userdata).'",
            "'.mysql_real_escape_string($usersessionid).'",
            "'.mysql_real_escape_string($session).'",
            "'.mysql_real_escape_string($emptyhashid).'",
            "'.mysql_real_escape_string($emptydatarow).'",
            "'.mysql_real_escape_string($emptyuserdata).'",
            "'.mysql_real_escape_string($sidmismatch).'",
            "'.intval($litime, 10).'",
            "'.mysql_real_escape_string($username).'",
            "'.mysql_real_escape_string($cfk).'",
            "'.mysql_real_escape_string($enc).'",
            "'.mysql_real_escape_string($phpsessionid).'"
        )';
                    //die($query);
        $vars['db']['handler']->query($query);

	$log_insert_id = $vars['db']['handler']->last_id();
        return $log_insert_id;
    }




    static function log_login(&$vars, $table, $data, $state='unspecified', $userdata='', $usersessionid='', $session='', $litime=0, $username='', $cfk='', $enc='', $phpsessionid='') {
        $query = '
        INSERT INTO `'.DB_DB_TPT_LOGS.'`.`'.$table.'`
        (
            `timestamp`,
            `ip`,
            `userid`,
            `request_method`,
            `url`,
            `data`,
            `user_agent`,
            `referrer`,
            `state`,
            `userdata`,
            `usersessionid`,
            `session`,
            `litime`,
            `username`,
            `enc`,
            `phpsessionid`
        ) VALUES
        (
            '.$vars['environment']['request_time'].',
            "'.$vars['user']['client_ip'].'",
            '.intval($vars['user']['userid'], 10).',
            "'.mysql_real_escape_string($vars['environment']['request_method']).'",
            "'.mysql_real_escape_string($vars['config']['requesturl']).'",
            "'.mysql_real_escape_string($data).'",
            "'.mysql_real_escape_string($vars['environment']['http_user_agent']).'",
            "'.mysql_real_escape_string($vars['environment']['http_referrer']).'",
            "'.mysql_real_escape_string($state).'",
            "'.mysql_real_escape_string($userdata).'",
            "'.mysql_real_escape_string($usersessionid).'",
            "'.mysql_real_escape_string($session).'",
            "'.intval($litime, 10).'",
            "'.mysql_real_escape_string($username).'",
            "'.mysql_real_escape_string($enc).'",
            "'.mysql_real_escape_string($phpsessionid).'"
        )';
                    //die($query);
        $vars['db']['handler']->query($query);

		$log_insert_id = $vars['db']['handler']->last_id();
        return $log_insert_id;
    }





    function afterContent(&$vars) {
		//tpt_dump(isDevLog());
		//tpt_dump($vars['config']['dev']['logger']['db_rq_log']);
		//tpt_dump($vars['config']['dev']['logger']['db_rq_log_requests_dev']);
		//tpt_dump('logging', true);
        if(!empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_requests'])) {
            if(!empty($vars['environment']['is404']) || empty($vars['environment']['page_rule'])) {
                if($vars['environment']['request_method'] == 'get') {
                    //$postdata = file_get_contents("php://input");
					//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
					$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_get404", '', session_id());
                } else if($vars['environment']['request_method'] == 'post') {
                    $postdata = file_get_contents("php://input");
                    //$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
					$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_post404", $postdata, session_id());
                } else {
                    $postdata = file_get_contents("php://input");
					//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
					$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_other404", $postdata, session_id());
                }
            } else {
                if(!empty($vars['environment']['page_rule']['is_ajax'])) {
                    if($vars['environment']['request_method'] == 'get') {
                        //$postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_getajax", '', session_id());
                    } else if($vars['environment']['request_method'] == 'post') {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_postajax", $postdata, session_id());
                    } else {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_otherajax", $postdata, session_id());
                    }
                } else {
                    if($vars['environment']['request_method'] == 'get') {
                        //$postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_get", '', session_id());
                    } else if($vars['environment']['request_method'] == 'post') {
                        //$postdata = file_get_contents("php://input");
						$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_post", $postdata, session_id());
                    } else {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
						$vars['stats']['request_id'] = self::log_request($vars, "tpt_request_rq_otherajax", $postdata, session_id());
                    }
                }
            }
            //die();

        }

        if(isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_requests_dev'])) {
            //file_put_contents(dirname(__FILE__).DS.'dev-debug.txt', $_SERVER['REQUEST_URI']."\n", FILE_APPEND);
            //tpt_dump($_SERVER['REQUEST_URI'], true);
            //tpt_dump($tpt_vars['environment']['is404']);
            //tpt_dump($tpt_vars['environment']['page_rule']);

            if(!empty($vars['environment']['is404']) || empty($vars['environment']['page_rule'])) {
                if($vars['environment']['request_method'] == 'get') {
                    //$postdata = file_get_contents("php://input");
					//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                    self::log_request($vars, "tpt_request_rq_get404_dev", '', session_id());
                } else if($vars['environment']['request_method'] == 'post') {
                    $postdata = file_get_contents("php://input");
					//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                    self::log_request($vars, "tpt_request_rq_post404_dev", $postdata, session_id());
                } else {
                    $postdata = file_get_contents("php://input");
					//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                    self::log_request($vars, "tpt_request_rq_other404_dev", $postdata, session_id());
                }
            } else {
                if(!empty($vars['environment']['page_rule']['is_ajax'])) {
                    if($vars['environment']['request_method'] == 'get') {
                        //$postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_getajax_dev", '', session_id());
                    } else if($vars['environment']['request_method'] == 'post') {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_postajax_dev", $postdata, session_id());
                    } else {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_otherajax_dev", $postdata, session_id());
                    }
                } else {
                    if($vars['environment']['request_method'] == 'get') {
                        //$postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_get_dev", '', session_id());
                    } else if($vars['environment']['request_method'] == 'post') {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_post_dev", $postdata, session_id());
                    } else {
                        $postdata = file_get_contents("php://input");
						//$postdata = tpt_security::encode_string2($vars, file_get_contents("php://input"));
                        self::log_request($vars, "tpt_request_rq_other_dev", $postdata, session_id());
                    }
                }
            }
        }

        /*
        if(!empty(self::$dump_content)) {
            //die();
            //tpt_dump(self::$dump_content, true);
            $tcontent = array_column(self::$dump_content, 'header');
            $_tmp = array_column(self::$dump_content, 'content');
            $btcontent = array_column(self::$dump_content, 'backtrace');
            $dcontent = array();
            foreach($_tmp as $key=>$dc) {
                $bt = is_string($btcontent[$key])?$btcontent[$key]:var_export($btcontent[$key], true);
                $dcontent[$key] = <<< EOT
<div class="padding-5" style="border: 1px solid #AAA;">
$bt
</div>
$dc
EOT;
            }
            //tpt_dump(self::$dump_content, true);
            //tpt_dump($dcontent, true);
            $tcontent_attribs = array_fill(0, count($dcontent), 'onclick="var a=getChildElements(document.getElementById(\'log_dump\'));var tabs=getChildElements(a[0]);var panels=getChildElements(a[1]);for(var i=0, _length=tabs.length; i<_length; i++){if(this==tabs[i]){addClass(panels[i], \'active\');}else{removeClass(panels[i], \'active\');}removeClass(tabs[i], \'active\');}addClass(this, \'active\');" style="border-width: 1px 1px 1px 1px; border-style: solid; border-color: #AAA #AAA #666 #AAA;"');
            //array_unshift($tcontent_attribs, '');
            $tcontent = tpt_html::getAlternatingHTML($tcontent,
                                                     "div",
                                                     array('float-left padding-top-5 padding-left-5 padding-bottom-5 padding-right-5'), //$classes=
                                                     array(), //$inpcls=
                                                     $tcontent_attribs); //$htmlAttribs

            $dcontent_classes = array();
            if(count($dcontent) > 1) {
                $dcontent_classes = array_fill(0, count($dcontent)-1, 'position-absolute padding-top-5 padding-left-5 padding-bottom-5 padding-right-5');
            }
            array_unshift($dcontent_classes, 'position-relative padding-top-5 padding-left-5 padding-bottom-5 padding-right-5');
            $dcontent = tpt_html::getAlternatingHTML($dcontent,
                                                     "pre",
                                                     $dcontent_classes, //$classes=
                                                     array(), //$inpcls=
                                                     array('style="border-width: 1px 1px 1px 1px; border-style: solid; border-color: #AAA #AAA #AAA #AAA; border-radius: 3px 3px 3px 3px;"') //$htmlAttribs=
                                                     );

            $vars['template_data']['footer_scripts']['content'][] = <<< EOT
<div class="position-absolute" style="left: 50%; width: 600px; top: 20px; z-index: 10000000;">
    <div id="log_dump" class="tabs position-relative" style="background: #666 none; left: -50%;">
        <div class="position-relative clearFix padding-top-5 padding-left-5 padding-bottom-0 padding-right-5" style="color: #FFF; top: 1px;">
            $tcontent
        </div>
        <div class="position-relative padding-top-0 padding-left-5 padding-bottom-5 padding-right-5" style="color: #FFF;">
            $dcontent
        </div>
    </div>
</div>
EOT;
        }
        */

    }


    static function dump(&$vars, $var, $backtrace='', $file='', $line='') {
		//tpt_dump($vars['user']['client_ip']);
		//tpt_dump($vars['user'], true);
		//if(empty($vars['user'])) {
		//tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		//tpt_dump($vars['config']['var_dump_ips']);
		//self::dump($vars, $vars['config']['var_dump_ips'], debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), '$vars[\'config\'][\'var_dump_ips\']', __FILE__.' '.__LINE__);
		//    tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		//}
		if (empty($vars['environment']['mobile_device'])) {
			if (in_array($vars['user']['client_ip'], is_array($vars['config']['dev_console_ips']) ? $vars['config']['dev_console_ips'] : array())) {

				self::$dump_content[] = array(
					'backtrace' => '',
					'header' => '',
					'content' => '',
					'footer' => ''
				);
				$backtr = '';
				$header = '';
				$content = '';
				$footer = '';
				end(self::$dump_content);
				$last = key(self::$dump_content);
				if ($backtrace != '') {
					$backtr = self::$dump_content[$last]['backtrace'] = var_export($backtrace, true);
				}
				if ($file != '') {
					//self::$dump_content[$last]['header'] = '-------------------------------------'."\n";
					$header = self::$dump_content[$last]['header'] = self::$dump_content[$last]['header'] . $file;
					if ($line != '') {
						$header = self::$dump_content[$last]['header'] = self::$dump_content[$last]['header'] . ' (' . $line . ')';
					}
					//self::$dump_content[$last]['header'] .= "\n";
				}
				//ob_start();
				$varexp = var_export($var, true);
				//self::$dump_content[$last]['content'] = ob_get_clean();
				$content = self::$dump_content[$last]['content'] = htmlspecialchars($varexp);
				if ($file != '') {
					$footer = self::$dump_content[$last]['footer'] = '-------------------------------------' . "\n";
				}
				//if($die) {
				//	die();
				//}
				$console_tab = new tpt_adminTab($vars, $header, '<div class="padding-5" style="border: 1px solid #AAA;"><pre>' . $backtr . '</pre></div><pre>' . $content . '</pre>'/*, $module->getPagination($vars)*/);
				$vars['admin']['handler']->tabs[] = $console_tab;
				if (!defined('TPT_BACK')) {
					$vars['environment']['isAdmin'] = true;
				}
			}
		}
	}
}

$tpt_vars['environment']['url_processors'][] = $tpt_vars['environment']['logger']['handler'] = new tpt_logger($tpt_vars);
