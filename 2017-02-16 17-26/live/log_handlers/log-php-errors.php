<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database.php');

function log_error($num, $str, $file, $line, $context = null) {
    log_exception(new ErrorException($str, 0, $num, $file, $line));

	return false;
}

function log_fatal_error($num, $str, $file, $line, $context = null) {
	log_fatal_exception(new ErrorException($str, 0, $num, $file, $line));
}

function log_exception(Exception $e) {
    global $tpt_vars;
	// $stmt->bindParam(":message", $e->getMessage()); PHP Strict standards:  Only variables should be passed by reference in /home/amazingw/public_html/live/log_handlers/log-php-errors.php on line 21
	// please pass only variables to the bindParam function and never functions!
	$current_url = get_current_url();
	$message = $e->getMessage();
	$code = $e->getCode();
	$file = $e->getFile();
	$line = $e->getLine();
	$severity = $e->getSeverity();
	$date = date('Y-m-d H:i:s');
	$time = time();
	$rq_time = (isset($tpt_vars['environment']['request_time'])?$tpt_vars['environment']['request_time']:$time);
	$timezone = date('O');
	$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	$backtrace = var_export($backtrace, true);
	$ip = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'');
	$referrer = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
	$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'');
	$phpsessionid = session_id();
	//$headers = apache_request_headers();
	$headers = '';
	//$response_headers = apache_response_headers();
	$response_headers = '';
	$_SRV = var_export($_SERVER, true);

	//tpt_dump($query);
	//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	//error_reporting(E_ALL & ~E_DEPRECATED);
	//var_dump(error_reporting());
    if (error_reporting()) {
		//var_dump('asd');
        $database = new Database();
        $db = $database->getConnection();
        $query = "
INSERT INTO
	php_errors_log
SET
	request_timestamp=:request_timestamp,
	timestamp=:timestamp,
	date_time=:date_time,
	timezone=:timezone,
	message=:message,
	code=:code,
	file=:file,
	line=:line,
	severity=:severity,
	ip_address=:ip_address,
	backtrace=:backtrace,
	current_url=:current_url,
	referrer=:referrer,
	user_agent=:user_agent,
	phpsessionid=:phpsessionid,
	headers=:headers,
	response_headers=:response_headers,
	_SERVER=:_SERVER
";
		//tpt_dump($query, true);
        $stmt = $db->prepare($query);
		$stmt->bindParam(":request_timestamp", $rq_time);
		$stmt->bindParam(":timestamp", $time);
		$stmt->bindParam(":date_time", $date);
		$stmt->bindParam(":timezone", $timezone);
        $stmt->bindParam(":message", $message);
        $stmt->bindParam(":code", $code);
        $stmt->bindParam(":file", $file);
        $stmt->bindParam(":line", $line);
        $stmt->bindParam(":severity", $severity);
        $stmt->bindParam(":ip_address", $ip);
		$stmt->bindParam(":backtrace", $backtrace);
        $stmt->bindParam(":current_url", $current_url);
        $stmt->bindParam(":referrer", $referrer);
        $stmt->bindParam(":user_agent", $user_agent);
        $stmt->bindParam(":phpsessionid", $phpsessionid);
        $stmt->bindParam(":headers", $headers);
        $stmt->bindParam(":response_headers", $response_headers);
        $stmt->bindParam(":_SERVER", $_SRV);
        if (!$stmt->execute()) {
			//tpt_dump('asdsad', true);
            print_r($stmt->errorInfo());
            print_r($stmt->debugDumpParams());
        }
    }

	return false;
}

function log_fatal_exception(Exception $e) {
	global $tpt_vars;
	// $stmt->bindParam(":message", $e->getMessage()); PHP Strict standards:  Only variables should be passed by reference in /home/amazingw/public_html/live/log_handlers/log-php-errors.php on line 21
	// please pass only variables to the bindParam function and never functions!
	$current_url = get_current_url();
	$message = $e->getMessage();
	$code = $e->getCode();
	$file = $e->getFile();
	$line = $e->getLine();
	$severity = $e->getSeverity();
	$date = date('Y-m-d H:i:s');
	$time = time();
	$rq_time = (isset($tpt_vars['environment']['request_time'])?$tpt_vars['environment']['request_time']:$time);
	$timezone = date('O');
	$memory_usage = intval(memory_get_usage(), 10);
	$memory_limit = intval(ini_get('memory_limit'), 10);
	$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
	$backtrace = var_export($backtrace, true);
	$ip = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'');
	$referrer = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');
	$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'');
	$phpsessionid = session_id();
	//$headers = apache_request_headers();
	$headers = '';
	//$response_headers = apache_response_headers();
	$response_headers = '';
	$_SRV = var_export($_SERVER, true);

	if (error_reporting()) {
		$database = new Database();
		$db = $database->getConnection();
		$query = "
INSERT INTO
	php_fatal_errors_log
SET
	request_timestamp=:request_timestamp,
	timestamp=:timestamp,
	date_time=:date_time,
	timezone=:timezone,
	message=:message,
	code=:code,
	file=:file,
	line=:line,
	severity=:severity,
	ip_address=:ip_address,
	backtrace=:backtrace,
	current_url=:current_url,
	referrer=:referrer,
	user_agent=:user_agent,
	phpsessionid=:phpsessionid,
	headers=:headers,
	response_headers=:response_headers,
	_SERVER=:_SERVER,
	memory_usage=:memory_usage,
	memory_limit=:memory_limit
";

		$stmt = $db->prepare($query);
		$stmt->bindParam(":request_timestamp", $rq_time);
		$stmt->bindParam(":timestamp", $time);
		$stmt->bindParam(":date_time", $date);
		$stmt->bindParam(":timezone", $timezone);
		$stmt->bindParam(":message", $message);
		$stmt->bindParam(":code", $code);
		$stmt->bindParam(":file", $file);
		$stmt->bindParam(":line", $line);
		$stmt->bindParam(":severity", $severity);
		$stmt->bindParam(":ip_address", $ip);
		$stmt->bindParam(":backtrace", $backtrace);
		$stmt->bindParam(":current_url", $current_url);
		$stmt->bindParam(":referrer", $referrer);
		$stmt->bindParam(":user_agent", $user_agent);
		$stmt->bindParam(":phpsessionid", $phpsessionid);
		$stmt->bindParam(":headers", $headers);
		$stmt->bindParam(":response_headers", $response_headers);
		$stmt->bindParam(":_SERVER", $_SRV);
		$stmt->bindParam(":memory_usage", $memory_usage);
		$stmt->bindParam(":memory_limit", $memory_limit);
		if (!$stmt->execute()) {
			print_r($stmt->errorInfo());
			print_r($stmt->debugDumpParams());
		}
	}
}

function check_for_fatal() {
    $error = error_get_last();
    if ($error["type"] == E_ERROR) {
        log_error($error["type"], $error["message"], $error["file"], $error["line"]);
        log_fatal_error($error["type"], $error["message"], $error["file"], $error["line"]);
    }
}

function get_current_url() {
	$url = 'CLI: '.(isset($argv)?$argv[0]:'');
	if(isset($_SERVER['HTTP_HOST'])) {
		$url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}
    return $url;
}

//tpt_dump(error_reporting());
register_shutdown_function("check_for_fatal");
set_error_handler("log_error");
set_exception_handler("log_exception");
//ini_set("display_errors", "off");
error_reporting(E_ALL);
