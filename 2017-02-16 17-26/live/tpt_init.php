<?php
defined('TPT_INIT') or die('access denied');


$dmps = '';
function shutdown(){
	global $dmps;
	echo $dmps;
//var_dump(error_get_last());
}

//if ($_SERVER['REMOTE_ADDR']=='109.160.0.218' || $_SERVER['REMOTE_ADDR']=='109.120.245.125') {
//if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') {
if (false && isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR']=='89.253.189.155')) {
	register_shutdown_function('shutdown');
}


//var_dump($tpt_vars['config']['debug_ips']);die();


/*
if(!class_exists('JFactory')) {
    define( '_JEXEC', 1 );
    define('JPATH_BASE', dirname(dirname(__FILE__)));
    defined('DS') or define( 'DS', DIRECTORY_SEPARATOR );
    require_once (JPATH_BASE . DS . 'includes' . DS . 'defines.php');
    require_once (JPATH_BASE . DS . 'includes' . DS . 'framework.php');

    $mainframe = JFactory::getApplication('site');
}
*/
//die('asdasdadsadssa');
//if(($_SERVER['REMOTE_ADDR'] == '85.130.3.155') || ($_SERVER['REMOTE_ADDR'] == '24.132.226.94')) {}
// for testing purposes :)

//********************************************** START APPLICATION
//set_include_path(get_include_path() . PATH_SEPARATOR . '/home/amazingw/public_html');
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




include(dirname(__FILE__).DIRECTORY_SEPARATOR.'defines.php');
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.php');

$tpt_vars['url'] = array();
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_url.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_pricing.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_basket.php');



include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'functions.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'base32.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_functions.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_template.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_navigation.php');
//tpt_dump('Beginning');
//tpt_dump(number_format(memory_get_usage()));
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_html.php');
$db_wrapper_file = TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_database.php';
require_once($db_wrapper_file);
$tpt_vars['db']['handler'] = $db = new tpt_Database($tpt_vars['config']['db']['host'], $tpt_vars['config']['db']['user'], $tpt_vars['config']['db']['password'], $tpt_vars['config']['db']['database']);

$tpt_vars['environment']['url_processors'] = array();
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_logger.php');

// ajax specific
$tpt_vars['environment']['client'] = 0;
$tpt_vars['environment']['mobile_template'] = 0;
$tpt_vars['environment']['mobile_device'] = 0;
$tpt_vars['environment']['isAjax'] = false;
$tpt_vars['environment']['force404'] = false;
$tpt_vars['environment']['isRedirect'] = false;
$tpt_vars['environment']['is404'] = false;
$tpt_vars['environment']['isTask'] = false;
$tpt_vars['environment']['ajax_call'] = array();
$tpt_vars['environment']['ajax_call']['task'] = '';
$tpt_vars['environment']['ajax_result'] = array();
$tpt_vars['environment']['ajax_result']['action'] = array();
$tpt_vars['environment']['ajax_result']['update_elements'] = array();
$tpt_vars['environment']['ajax_result']['append_html'] = array();
$tpt_vars['environment']['ajax_result']['add_style'] = array();
$tpt_vars['environment']['ajax_result']['exec_script'] = array();
$tpt_vars['environment']['ajax_result']['messages'] = array();
$tpt_vars['environment']['ajax_result']['execute_onload'] = array();
$tpt_vars['environment']['ajax_result']['execute_onload']['head'] = array();
$tpt_vars['environment']['ajax_result']['execute_onload']['footer'] = array();
$tpt_vars['environment']['ajax_response'] = '';



$tpt_vars['environment']['isMobileDevice'] = array();

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_mobiledetect.php');
/*if((isDev('mobiletemplate') && ($tpt_vars['environment']['isMobileDevice']['ipod'] ||
	$tpt_vars['environment']['isMobileDevice']['ipad'] ||
	$tpt_vars['environment']['isMobileDevice']['iphone'] ||
	$tpt_vars['environment']['isMobileDevice']['android'] ||
	$tpt_vars['environment']['isMobileDevice']['webos'])) || !empty($_GET['mobiletest'])) {
	$tpt_vars['environment']['mobile_template'] = true;
}*/

if((($tpt_vars['environment']['isMobileDevice']['ipod'] ||
	$tpt_vars['environment']['isMobileDevice']['ipad'] ||
	$tpt_vars['environment']['isMobileDevice']['iphone'] ||
	$tpt_vars['environment']['isMobileDevice']['android'] ||
	$tpt_vars['environment']['isMobileDevice']['webos']))) {
	$tpt_vars['environment']['mobile_device'] = 1;
}
//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'mobile_useragent_check.php');

if(!defined('TPT_BACK') && (PHP_SAPI != 'cli')) {
	if (session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}
}

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
//var_dump(ini_get('display_startup_errors'));
//var_dump(ini_get('display_errors'));
//var_dump(error_reporting());
//echo $asd;

$tpt_vars['db']['tables'] = $tpt_vars['db']['handler']->get_tables();
if(is_array($tpt_vars['db']['tables']))
	$tpt_vars['db']['tables'] = array_combine($tpt_vars['db']['tables'], $tpt_vars['db']['tables']);



include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_modulefield.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_module.php');
//tpt_dump('Before modules');
//tpt_dump(number_format(memory_get_usage()));
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_modules.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_security.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_request.php');

$tpt_vars['data'] = array();
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_messages.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_ajax.php');
//tpt_dump($tpt_vars['data'], true);

//tpt_dump($tpt_vars['config']['requesturl']);
//tpt_dump($_SESSION['customer_area']);
if(($tpt_vars['environment']['request_method'] == 'get') && (!isDev('rebuildcache') || empty($_GET['rebuildcache']))) {
	$where = '';
	if(((in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['urlrule_page_dev_file_ips'])))) {
		//tpt_dump('asd', true);
		$where = ' AND (`dev_uncache`!=1 OR `dev_uncache` IS NULL)';
	}
	$query = 'SELECT * FROM `tpt_cache_pre` WHERE `enabled`=1 AND `url`="' . mysql_real_escape_string($tpt_vars['config']['requesturl']) . '" '.$where;
	//tpt_dump($query, true, 'M');
	$db->query($query);
	$cache = $db->fetch_assoc();

	$ccontent = '';
	if (!empty($cache)) {
		$query = 'SELECT * FROM `tpt_module_urlrules` WHERE `id`=' . $cache['urlrule_id'];
		$db->query($query);
		$tpt_vars['environment']['page_rule'] = $db->fetch_assoc();

		if(
		(
			(
				!empty($tpt_vars['environment']['page_rule']['use_mobile_template'])
				||
				(!empty($tpt_vars['environment']['page_rule']['use_mobile_template_dev']) && isDev('use_mobile_template_dev'))
			)
			&&
			!empty($tpt_vars['environment']['mobile_device'])
		)
		) {
			//tpt_dump('asdasdasd', true);
			$tpt_vars['environment']['mobile_template'] = 1;
		}
		if(!empty($_GET['mobiletest'])) {
			$tpt_vars['environment']['mobile_template'] = intval($_GET['mobiletest'], 10);
		}

		$tpt_vars['modules'] = array();
		$tpt_vars['modules']['handler'] = new tpt_Modules($tpt_vars);

		tpt_current_user::setReturnURLCookies($tpt_vars);
		//tpt_dump($tpt_vars['environment']['mobile_template']);
		//tpt_dump($tpt_vars['environment']['page_rule'], true);

		if (!empty($tpt_vars['environment']['mobile_template'])) {
			//tpt_dump('asd');
			$ccontent = $cache['content_mobile'.$tpt_vars['environment']['mobile_template']];
		} else {
			//tpt_dump('asd');
			$ccontent = $cache['content'];
		}
		//tpt_dump('asd');

		if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_requests'])) {
			tpt_logger::log_request($tpt_vars, "tpt_request_rq_get_cached", '', session_id());
		}

		if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_requests_dev'])) {
			tpt_logger::log_request($tpt_vars, "tpt_request_rq_get_cached_dev", '', session_id());
		}

		$tpt_tasks_head_content = tpt_template::getFrontendTptTasksHeadContent($tpt_vars);
		$customer_area = tpt_template::getCachedFrontendHeaderCustomerArea($tpt_vars);
		$shopperapprovedschema = tpt_template::getCachedShopperApprovedSchema($tpt_vars);
		$ccontent = str_replace(TPT_TAG_TPTTASKSHEADCONTENT, $tpt_tasks_head_content, $ccontent);
		$ccontent = str_replace(TPT_TAG_CUSTOMERAREA, $customer_area, $ccontent);
		$ccontent = str_replace(TPT_TAG_MESSAGES, '', $ccontent);
		$ccontent = str_replace(TPT_TAG_SHOPPERAPPROVEDSCHEMACACHE, $shopperapprovedschema, $ccontent);
		die($ccontent);
	}
}



include_once(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_cache.php');


//************ PARSE REQUEST_URI
function tpt_af($var) {
	return(!empty($var));
}

global $af_bystr;
$af_bystr = '';
function tpt_af_bystr($var) {
	global $af_bystr;
	return(strstr($var, $af_bystr) === false);
}
global $af_byreg;
$af_byreg = '#^$#';
function tpt_af_byreg($var) {
	global $af_byreg;
	preg_match($af_byreg, $var, $mtch);
	return(empty($mtch));
}


$tpt_vars['url']['rurl'] = $tpt_vars['config']['requesturl'];
//var_dump($tpt_vars['url']['rurl']);die();
//$rurl = 'http://amazingwristbands.com/live/custom-wristbands-quote.php';
$tpt_vars['url']['purl'] = parse_url(BASE_URL.$tpt_vars['url']['rurl']);
//var_dump(BASE_URL.$tpt_vars['url']['rurl']);die();
//var_dump($tpt_vars['url']['purl']);die();
if(isset($tpt_vars['url']['purl']['path'])) {
    $tpt_vars['url']['path'] = $tpt_vars['url']['purl']['path'];
}
$tpt_vars['url']['bpath'] = array(); // segments array
//$tpt_vars['url']['qry'] = !empty($tpt_vars['url']['purl']['query'])?explode('&', $tpt_vars['url']['purl']['query']):array();
$tpt_vars['url']['qry'] = array();
if(!empty($tpt_vars['url']['purl']['query'])) {
	parse_str($tpt_vars['url']['purl']['query'], $tpt_vars['url']['qry']);
}
//var_dump($tpt_vars['url']['path']);
$tpt_vars['url']['bpath'] = array();
$tpt_vars['url']['upath'] = '';
$tpt_vars['url']['ubpath'] = array();
if(!empty($tpt_vars['url']['path'])) { // "/live/category/product" -> array("category", "product")
	$tpt_vars['url']['bpath'] = explode('/', $tpt_vars['url']['path']); // split url to segments array by path delimiter /
	$tpt_vars['url']['ubpath'] = $tpt_vars['url']['bpath']; // unfiltered bpath
	$tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af'); // filter out empty segments from duplicate path delimiters

	$firstSegment = reset($tpt_vars['url']['bpath']);
	if($firstSegment != $tpt_vars['config']['subpath_cfg']) {
		$tpt_vars['config']['rooturl'] = $tpt_rooturl = $tpt_vars['config']['baseurl'] = $tpt_baseurl = $tpt_vars['config']['rooturl'];
	} else {
		$tpt_vars['config']['subpath'] = $tpt_vars['config']['subpath_cfg'];
	}

	if(!empty($tpt_vars['config']['subpath']) && ($firstSegment == $tpt_vars['config']['subpath'])) {
		array_shift($tpt_vars['url']['bpath']); // rewrite_base php simulation (removes the first segment if a $tpt_vars['config']['subpath'] is set)
	}

	$tpt_vars['url']['upath'] = implode('/', $tpt_vars['url']['ubpath']); // unfiltered path
}

//var_dump($tpt_vars['url']['ubpath']);
//var_dump($tpt_vars['url']['upath']);die();
$tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // the base path that will be used when rebuilding the href links
$tpt_vars['config']['ajaxurl'] = $tpt_ajaxurl = $tpt_vars['config']['ajaxurl'].'/'.$tpt_vars['url']['path'];
if($tpt_vars['config']['ajaxurl'] == '/')
	$tpt_vars['config']['ajaxurl'] = $tpt_ajaxurl = substr($tpt_vars['config']['ajaxurl'], 0, strlen($tpt_vars['config']['ajaxurl'])-1);
$pajax_url = parse_url($tpt_ajaxurl);
$tpt_vars['config']['ajaxurl'] = $tpt_ajaxurl = $pajax_url['scheme'].'://'.$pajax_url['host'].$pajax_url['path'];
//$au_scheme = 'http://';
//if (strtolower($_SERVER['HTTPS']) == "on") {
//$au_scheme = 'https://';
//}
//$tpt_vars['config']['ajaxurl'] = $tpt_ajaxurl = $au_scheme.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

///////////// END PARSE REQUEST_URI */

//************ INITIALIZE GLOBAL FRAMEWORK VARS
$tpt_vars['_temp'] = array(); // temporary storage
$tpt_vars['modules'] = array();


$tpt_vars['logic'] = array();

// get url matching rules
$tpt_vars['data']['tpt_module_urlrules'] = array();
$tpt_vars['data']['tpt_module_urlrules']['id'] = array('process'=>array(), 'passthrough'=>array());
$query = 'SELECT * FROM `tpt_module_urlrules` WHERE `process_url`>0 AND `enabled`=1 ORDER BY `priority` ASC';
$db->query($query);
$tpt_vars['data']['tpt_module_urlrules']['id'] = array();
$tpt_vars['data']['tpt_module_urlrules']['id']['process'] = $db->fetch_assoc_list('id', false);

$query = 'SELECT * FROM `tpt_module_urlrules` WHERE `process_url`=0 AND `enabled`=1 ORDER BY `priority` ASC';
$db->query($query);
$tpt_vars['data']['tpt_module_urlrules']['id']['passthrough'] = $db->fetch_assoc_list('id', false);

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

// Admin
$tpt_vars['environment']['isAdmin'] = false;
$tpt_vars['environment']['isAdministration'] = false;
$tpt_vars['admin'] = array();
$tpt_vars['admin']['template_data'] = array();
$tpt_vars['admin']['template_data']['panel_max_top_absolute'] = -139;

// template data holder - modules should use this array to build their html from processed array data
$tpt_vars['template_data'] = array();
$tpt_vars['template_data']['links'] = array();
$tpt_vars['template_data']['messages'] = array();
$tpt_vars['template_data']['valid_form'] = true;
$tpt_vars['template_data']['invalid_fields'] = array();
$tpt_vars['template_data']['form_values'] = array();
$tpt_vars['template_data']['processed_form_values'] = array();
$tpt_vars['template_data']['isFrame'] = false;
$tpt_vars['template_data']['hasLeftBar'] = false;
$tpt_vars['template_data']['hasSocialBar'] = false;
$tpt_vars['template_data']['template_type'] = 'default';
$tpt_vars['template_data']['body_tag_start'] = array();
$tpt_vars['template_data']['body_tag_start']['scripts'] = array();
$tpt_vars['template_data']['body_tag_start']['content'] = array();
$tpt_vars['template_data']['footer_scripts'] = array();
$tpt_vars['template_data']['footer_scripts']['scripts'] = array();
$tpt_vars['template_data']['footer_scripts']['style'] = array();
$tpt_vars['template_data']['footer_scripts']['content'] = array();
$tpt_vars['template_data']['head'] = array(
	'start'=>'',
	'google_tag_manager'=>'',
	'style_script1'=>'',
	'style_script2'=>'',
);
$tpt_vars['template_data']['meta'] = array();


///////////// END INITIALIZE GLOBAL FRAMEWORK VARS */
//************ INITIALIZE TEMPLATE HTML CONTENT VARS
$tpt_vars['navigation'] = array();
$tpt_vars['template'] = array();

$tpt_vars['template']['id'] = array();

$tpt_vars['template']['css_style'] = array();
$tpt_vars['template']['css_style']['body'] = '';

$tpt_vars['template']['css_class'] = array();
$tpt_vars['template']['css_class']['outer-wrapper'] = 'width-900 padding-right-100';
$tpt_vars['template']['css_class']['content'] = 'width-688';

$tpt_vars['template']['head_html'] = '';
$tpt_vars['template']['meta_html'] = '';

$tpt_vars['template']['header'] = '';
$tpt_vars['template']['content'] = '';
$content = '';
$tpt_vars['template']['footer'] = '';
$tpt_vars['template']['social_bar'] = '';
$tpt_vars['template']['left_bar'] = '';
$tpt_vars['template']['title'] = '';
$tpt_vars['template']['tooltips'] = '';
$tpt_vars['template']['home_href'] = BASE_URL;
$tpt_vars['template']['quote_link'] = '';
// Admin
$tpt_vars['template']['admin_panel'] = '';
$tpt_vars['template']['admin_content'] = '';
///////////// END INITIALIZE TEMPLATE HTML CONTENT VARS */
//tpt_dump('Before includes');
//tpt_dump(number_format(memory_get_usage()));

//************ INCLUDE FRAMEWORK FILES AND GET SOME TEMPLATE CONTENT
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_mail.php');
//tpt_dump($tpt_vars['config'], true);
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_cookies.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_session.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_links.php');



include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_previewgenerator.php');

//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_pricing_smart.php');
//} else {

//}
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_checkout.php');

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_admin.php');




include_once(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_leftnav.php');

//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'mobile_functions_for_popup.php');


tpt_template::getHeadContent($tpt_vars);


include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_foot.php');
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_platform_dependent.php');

//INCLUDE PAYMENT CLASSES
//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'payment_classes'.DIRECTORY_SEPARATOR.'PayPal'.DIRECTORY_SEPARATOR.'PayPal.php');
//include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'payment_classes'.DIRECTORY_SEPARATOR.'Auth.net'.DIRECTORY_SEPARATOR.'amzAuthNet.php');

include(TPT_LIB_DIR.DIRECTORY_SEPARATOR.'JSMin'.DIRECTORY_SEPARATOR.'JSMin.php');
///////////// END INCLUDE FRAMEWORK FILES AND GET SOME TEMPLATE CONTENT */
//tpt_dump('After includes');
//tpt_dump(number_format(memory_get_usage()));
//************ SET GLOBAL <HEAD> CODE

/*********************** OLD JQUERY ZOOM SCRIPT *********
url rules ids which contained the script
1,2,3,4,17,18,19,21,22,23,24,34,40

<script type='text/javascript' src='js/jquery.jqzoom-core.js'></script>
<link rel="stylesheet" type="text/css" href="css/jquery.jqzoom.css" />
<script>
$(document).ready(function(){
$('.zoom_class').jqzoom({
zoomType: 'standard',
alwaysOn : false,
zoomWidth: 400,
zoomHeight:300,
position:'left',
xOffset:5,
yOffset:0
});
});
</script>
 */
///////////////////////////////////////////////




//************ URL MATCHING AND PAGE PREPARATION
$tpt_vars['logic']['main_include_file'] = '';
$tpt_vars['environment']['page_rule'] = array();
$tpt_vars['environment']['page_rule']['id'] = 0;
//$status = false;

// initialze modules
//$tpt_vars['modules']['handler'] = $tpt_vars['environment']['url_processors'][] = new tpt_Modules($tpt_vars);

//tpt_dump($tpt_vars['modules']['handler']);
//die('asd');
$tpt_vars['modules']['handler'] = new tpt_Modules($tpt_vars);
//$tpt_vars['modules']['handler']->beforeContent($tpt_vars);

//tpt_dump('asd', true);


if(isDev('rebuildresources') && !empty($_GET['rebuildresources'])) {
	tpt_template::rebuildHeadContent($tpt_vars);
}

if(isDev('rebuildstaticpagescache') && !empty($_GET['rebuildstaticpagescache'])) {
	if($_GET['rebuildstaticpagescache'] == 1) {
		file_get_contents('https://www.amazingwristbands.com/?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/wristbands-no-minimum-custom-izer?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/wristbands-no-minimum-custom-izer?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/wristbands-no-minimum-custom-izer?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Debossed-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Debossed-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Debossed-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Ink-Filled-Debossed-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Ink-Filled-Debossed-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Ink-Filled-Debossed-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Dual-Layer-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Dual-Layer-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Dual-Layer-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Embossed-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Embossed-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Embossed-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Colorized-Emboss-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Colorized-Emboss-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Colorized-Emboss-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Screen-Printed-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Screen-Printed-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Screen-Printed-Wristbands?rebuildcache=1&mobiletest=2');
	}
	if($_GET['rebuildstaticpagescache'] == 2) {
		file_get_contents('https://www.amazingwristbands.com/Writable-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Writable-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Writable-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Rush-Order-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Rush-Order-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Rush-Order-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Standard-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Standard-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Standard-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/1-Inch-Extra-Wide-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/1-Inch-Extra-Wide-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/1-Inch-Extra-Wide-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/3-4-Inch-Wide-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/3-4-Inch-Wide-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/3-4-Inch-Wide-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Thin-Wristbands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Thin-Wristbands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Thin-Wristbands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Slap-Bands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Slap-Bands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Slap-Bands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Adjustable-Snap-Bracelets?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Adjustable-Snap-Bracelets?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Adjustable-Snap-Bracelets?rebuildcache=1&mobiletest=2');
	}
	if($_GET['rebuildstaticpagescache'] == 3) {
		file_get_contents('https://www.amazingwristbands.com/usb-Bands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/usb-Bands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/usb-Bands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/LED-Bands?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/LED-Bands?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/LED-Bands?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Rings?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Rings?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Rings?rebuildcache=1&mobiletest=2');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Key-Chains?rebuildcache=1');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Key-Chains?rebuildcache=1&mobiletest=1');
		file_get_contents('https://www.amazingwristbands.com/Silicone-Key-Chains?rebuildcache=1&mobiletest=2');
	}


}



//tpt_dump($tpt_vars['environment']['login_return_url']);


//tpt_dump('asdasdasd', true);
$tpt_vars['stats']['memory_usage']['after_init'] = intval(memory_get_usage(), 10);