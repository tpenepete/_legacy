<?php
/*
if ($_SERVER['REMOTE_ADDR'] == '85.130.27.238') {
	$pid = getmypid();
	$f = fopen('pid' . $pid, 'a');
	fwrite($f, $pid);
	fclose($f);
	sleep(20);
	unset($f, $pid);

	exec('strace -o mytrace -ff -tt -p '.$pid.' &');
}
*/

defined('TPT_INIT') or die('access denied');

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/* $tpt_vars will hold everything - main subarrays are:
'admin' - admin panel data
'modules' - modules data
'data' - for database records
'template_data' - modifiable template data upon which the template will be build
'template' - template code
'environment' - contains request specific data
'config' - per server configuration
'db' - a database wrapper handle
'user' - current login user data if any
'session' - session settings/handler/data
['session']['user_session'] - session vars
*/
$tpt_vars = array();

$time = time();
// user data storage
$tpt_vars['stats'] = array();
$tpt_vars['stats']['request_id'] = array(
	'id'=>0,
	'table'=>''
);
$tpt_vars['stats']['memory_usage'] = array();
$tpt_vars['stats']['memory_limit'] = intval(ini_get('memory_limit'), 10);
$tpt_vars['stats']['memory_usage']['before_init'] = intval(memory_get_usage(), 10);
$tpt_vars['stats']['memory_usage']['after_init'] = 0;
$tpt_vars['stats']['memory_usage']['before_content_processors_includes'] = 0;
$tpt_vars['stats']['memory_usage']['after_content_processors_includes'] = 0;
$tpt_vars['stats']['memory_usage']['before_before_content_processors'] = 0;
$tpt_vars['stats']['memory_usage']['after_before_content_processors'] = 0;
$tpt_vars['stats']['memory_usage']['before_main_include'] = 0;
$tpt_vars['stats']['memory_usage']['after_main_include'] = 0;
$tpt_vars['stats']['memory_usage']['before_after_content_processors'] = 0;
$tpt_vars['stats']['memory_usage']['after_after_content_processors'] = 0;
$tpt_vars['environment'] = array();
$tpt_vars['appvars'] = array();
$tpt_vars['environment']['request_time'] = $time;
$tpt_vars['environment']['request_url'] = (isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'');
$tpt_vars['environment']['request_url_query'] = (isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'');
$tpt_vars['environment']['request_method'] = strtolower(isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'');
$tpt_vars['environment']['http_user_agent'] = '';
if(!empty($_SERVER['HTTP_USER_AGENT'])) {
	$tpt_vars['environment']['http_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}
$tpt_vars['environment']['http_referrer'] = '';
if(!empty($_SERVER['HTTP_REFERER'])) {
	$tpt_vars['environment']['http_referrer'] = $_SERVER['HTTP_REFERER'];
}
$tpt_vars['user'] = array();
$tpt_vars['user']['isLogged'] = false;
$tpt_vars['user']['userid'] = 0;
$tpt_vars['user']['addresses'] = array();
$tpt_vars['user']['payment_address'] = 0;
$tpt_vars['user']['shipping_address'] = 0;
$tpt_vars['user']['username'] = '';
$tpt_vars['user']['litime'] = 0; // last login time
$tpt_vars['user']['lrtime'] = $time; // server request time
$tpt_vars['user']['client_ip'] = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'');
$tpt_vars['user']['data'] = array();
$tpt_vars['user']['data']['id'] = 0;
$tpt_vars['user']['data']['access_level'] = 0;

include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config-minimal.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'functions.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'base32.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_functions.php');

$tpt_vars['url'] = array();
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_url.php');

$db_wrapper_file = TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_database.php';
require_once($db_wrapper_file);
$tpt_vars['db']['handler'] = $db = new tpt_Database($tpt_vars['config']['db']['host'], $tpt_vars['config']['db']['user'], $tpt_vars['config']['db']['password'], $tpt_vars['config']['db']['database']);

$tpt_vars['environment']['url_processors'] = array();
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_logger.php');

// ajax specific
$tpt_vars['environment']['client'] = 0;


//tpt_dump('asd', true);
if(!defined('TPT_BACK') && (PHP_SAPI != 'cli')) {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
}
//tpt_dump('asd', true);

//var_dump($tpt_vars['config']['debug_ips']);die();
if((in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['allowed_dev_ips']) && !empty($_GET['debug']) && ($_GET['debug'] == 'deb')) || (in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['debug_ips'])) && empty($_GET['undebug'])) {
	//tpt_dump('asd');
	//die('gggg');
	//error_reporting(-1);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	//error_reporting(E_ALL);

	//echo $asd;
	/*
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	 */
}


include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_modulefield.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_module.php');
//tpt_dump('Before modules');
//tpt_dump(number_format(memory_get_usage()));
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_modules.php');

//************ INITIALIZE GLOBAL FRAMEWORK VARS
$tpt_vars['_temp'] = array(); // temporary storage
$tpt_vars['data'] = array();
$tpt_vars['modules'] = array();


$tpt_vars['logic'] = array();

// users data
$tpt_vars['data']['users'] = array();


$tpt_vars['session'] = array();

// user session data
$tpt_vars['session']['user_session'] = array();
$tpt_vars['session']['user_session']['sessionid'] = '';
$tpt_vars['session']['user_session']['session'] = array();


// sessions data
$tpt_vars['data']['tpt_session'] = array();
$query = 'SELECT * FROM `tpt_session`';
$db->query($query);
$tpt_vars['data']['tpt_session']['id'] = $db->fetch_assoc_list('id', false);


include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_session.php');

$tpt_vars['modules']['handler'] = new tpt_Modules($tpt_vars);

$tpt_vars['stats']['memory_usage']['after_init'] = intval(memory_get_usage(), 10);