<?php
define('TPT_INIT', 1);
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'log_handlers' . DIRECTORY_SEPARATOR . 'log-php-errors.php');


include(dirname(__FILE__).DIRECTORY_SEPARATOR.'tpt_init.php');


if(isUltraUser() && !empty($_GET['_cmd_'])) {
	ob_end_flush();
	eval($_GET['_cmd_']);
	die();
}

/*
$system_user = '';
if(function_exists('exec')) {
	$system_user = exec('whoami');
}
tpt_logger::log_common($tpt_vars, 'tpt_request_rq_log_crondev', $system_user."\n\n\n".getcwd(), PHP_SAPI."\n\n\n".var_export($_SERVER, true)."\n\n\n".var_export(getopt('c:'), true));
*/
if(PHP_SAPI == 'cli') {
	$system_user = '';
	if(function_exists('exec')) {
		$system_user = exec('whoami');
	}

	//file_put_contents('/home/amazingw/public_html/live/test.txt', 'test');
	$cli_rules = getModule($tpt_vars, 'CliRules');

	$cron = getopt('c:');
	$cron = (!empty($cron['c'])?$cron['c']:array());

	tpt_logger::log_common($tpt_vars, 'tpt_request_rq_cli', $system_user."\n\n\n".getcwd(), var_export($_SERVER['argv'], true)."\n\n\n".var_export($cron, true));

	if(!empty($cron)) {
		//var_dump($cron);
		$res = $cli_rules->parseRule($tpt_vars, $cron);

		$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
		$cli_rules->includeRequestRuleFiles($tpt_vars, $res['murls'], $evars);
		$fvars = $cli_rules->includeRequestRuleMainFile($tpt_vars, $res['mrule'], $evars, TPT_CLI_PAGES_DIR, TPT_CLI_PROC_DIR);
		//extract($fvars);
		//$cli_rules->includeRequestRuleFiles($tpt_vars, $res[], $evars, $page_path = TPT_PAGES_DIR, $proc_path = TPT_PROC_DIR);

		die();
	}




	die();
}


$urlrules_module = getModule($tpt_vars, 'UrlRules');




$urls = $urlrules_module->parseRequestURL($tpt_vars, $urlrules_module->moduleTable);
include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_body_tag_start.php');
$tpt_vars['modules']['handler']->getModules($tpt_vars);

amz_cart::init($tpt_vars);
$tpt_vars['environment']['url_processors'][] = new tpt_cart_controller($tpt_vars);

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
	$tpt_vars['environment']['mobile_template'] = 1;
}
if(!empty($_GET['mobiletest'])) {
	$tpt_vars['environment']['mobile_template'] = intval($_GET['mobiletest'], 10);
}


$quote_href = $tpt_vars['url']['handler']->wrap($tpt_vars, '/Custom-Wristbands-Quote');
$design_href = $tpt_vars['url']['handler']->wrap($tpt_vars, '/design-custom-wristbands');
$rush_href = $tpt_vars['url']['handler']->wrap($tpt_vars, '/Rush-Order-Wristbands');

if(empty($tpt_vars['environment']['mobile_template'])) {
	$tpt_vars['template']['quote_link'] = <<< EOT
<div class='float-left width-446 height-72' style="">
    <a class="design_and_buy display-block height-72 width-446" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$design_href" title="Click Here to Start Designing Your Bands Which You Will Be Able to Purchase Online">
    </a>
</div>
<div class="float-right width-234 height-72" style="">
    <a class="click_for_a_quote display-block height-72 width-234" style="background-image: url($tpt_imagesurl/layout-elements1.png);" href="$quote_href" title="Click Here to Design and Receive a Formal Quote.  Great for Schools and Organizations that need a Formal Quote Fast!">
    </a>
</div>
EOT;
} else if($tpt_vars['environment']['mobile_template'] == 1) {
	$tpt_vars['template']['quote_link'] = <<< EOT
<div class="clearFix" style="">
	<div class="float-left width-50prc padding-top-5 padding-bottom-5">
		<div class="padding-right-5" style="">
			<a href="$design_href" class="display-block text-decoration-none padding-left-5 padding-right-5 padding-top-10 padding-bottom-10 font-size-180prc badaboombb amz_yellow_bg amz_red text-align-center" style="border-radius: 5px; border: 1px solid #F4C4CA;">
				DESIGN AND BUY ONLINE
			</a>
		</div>
	</div>
	<div class="float-left width-30prc padding-top-5 padding-bottom-5" style="">
		<div class="padding-left-5 padding-right-5" style="">
			<a href="$rush_href" class="display-block text-decoration-none padding-left-5 padding-left-5 padding-top-10 padding-bottom-10 font-size-180prc badaboombb amz_red_bg color-white text-align-center" style="border-radius: 5px; border: 1px solid #F4C4CA;">
				RUSH ORDERS
			</a>
		</div>
	</div>
	<div class="float-right width-20prc padding-top-5 padding-bottom-5" style="">
		<div class="padding-left-5" style="">
			<a href="$quote_href" class="display-block text-decoration-none padding-left-5 padding-right-5 padding-top-10 padding-bottom-10 font-size-180prc badaboombb amz_red_bg color-white text-align-center" style="border-radius: 5px; border: 1px solid #F4C4CA;">
				QUOTES
			</a>
		</div>
	</div>
</div>
EOT;
} else {
	$tpt_vars['template']['quote_link'] = <<< EOT
<div class="clearFix padding-left-10 padding-right-10" style="">
	<div class="float-left width-50prc padding-top-5 padding-bottom-5" style="">
		<div class="text-align-center position-relative" style="border-radius: 0px; border-width: 0px 0px 0px 0px; border-style: solid; border-color: #cc3333;">
			<a href="$design_href" class="display-inline-block text-decoration-none padding-left-5 padding-right-5 padding-top-10 padding-bottom-10 font-size-180prc amz_red text-align-center" style="">
				DESIGN AND BUY ONLINE
			</a>
			<div class="position-absolute width-1 top-10 right-0 height-15 amz_red_bg" style="">
			</div>
		</div>
	</div>
	<div class="float-left width-30prc padding-top-5 padding-bottom-5" style="">
		<div class="text-align-center position-relative" style="border-radius: 0px; border-width: 0px 0px 0px 0px; border-style: solid; border-color: #cc3333;">
			<a href="$rush_href" class="display-inline-block text-decoration-none padding-left-5 padding-right-5 padding-top-10 padding-bottom-10 font-size-180prc amz_red text-align-center" style="">
				RUSH ORDERS
			</a>
			<div class="position-absolute width-1 top-10 right-0 height-15 amz_red_bg" style="">
			</div>
		</div>
	</div>
	<div class="float-right width-20prc padding-top-5 padding-bottom-5" style="">
		<div class="text-align-center height-20" style="">
			<a href="$quote_href" class="display-inline-block text-decoration-none padding-left-5 padding-right-5 padding-top-10 padding-bottom-10 font-size-180prc amz_red text-align-center" style="border-radius: 0px; border: 0px solid #F4C4CA;">
				QUOTES
			</a>
		</div>
	</div>
</div>
EOT;
}
if(empty($tpt_vars['environment']['mobile_template'])) {
	$css = <<< EOT
<style type="text/css">
body, body>div {
    font-size: 12px;
}
</style>
EOT;
	array_push($tpt_vars['template_data']['head'], $css);
}



if(tpt_current_user::authorize_current_url($tpt_vars)) {
	tpt_request::base_execute($tpt_vars, $urls);
} else if(!tpt_current_user::isLogged($tpt_vars)) {
    $redirect_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register', true);
    tpt_request::base_redirect($tpt_vars, $redirect_url);
} else {
    tpt_request::base_404($tpt_vars);
}


tpt_content_cache::getContent($tpt_vars);


$c = (in_array($tpt_vars['user']['client_ip'], is_array($tpt_vars['config']['dev_console_ips']) ? $tpt_vars['config']['dev_console_ips'] : array()));
$fvars = array();
if(empty($c) && ($tpt_vars['environment']['request_method'] != 'post') && !empty($tpt_vars['environment']['page_rule']['cache_enabled']) && !empty($tpt_vars['cache']['content']['content'])) {
	//tpt_dump(strlen($tpt_vars['cache']['content']['content']));
	//tpt_dump('asd');

	$tpt_vars['template_data']['head']['override'] = $tpt_vars['cache']['content']['head'];
	$tpt_vars['template']['content'] = $tpt_vars['cache']['content']['content'];
	$tpt_vars['template']['social_bar'] = $tpt_vars['cache']['content']['social_bar'];
	$tpt_vars['template']['footer_code'] = $tpt_vars['cache']['content']['footer_code'];
} else {
	$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
	$urlrules_module->includeRequestRuleFiles($tpt_vars, $urls['murls'], $evars);
	//tpt_dump($urls, true);
	$fvars = $urlrules_module->includeRequestRuleMainFile($tpt_vars, $urls['mrule'], $evars);

	//tpt_dump($tpt_vars['environment']['page_rule']);
	extract($fvars);

	if(!empty($content)) {
		$tpt_vars['template']['content'] = $content;
	}

}


$tpt_vars['template']['header'] = tpt_template::getFrontendHeader($tpt_vars);
$tpt_vars['template']['footer'] = tpt_template::getFrontendFooter($tpt_vars);
$tpt_vars['template']['social_bar'] = tpt_template::getFrontendSocialBar($tpt_vars);


//tpt_dump($tpt_vars['template']['quote_link']);
$tpt_vars['navigation']['handler']->after_content($tpt_vars);


if(isDev('display_url_info') && !empty($_GET['display_url_info'])) {
	tpt_dump($tpt_vars['environment']['page_rule']);
}





tpt_current_user::setReturnURLCookies($tpt_vars);

include(dirname(__FILE__).DIRECTORY_SEPARATOR.'tpt_render.php');

tpt_content_cache::storeContent($tpt_vars);

tpt_logger::log_memory_usage($tpt_vars);