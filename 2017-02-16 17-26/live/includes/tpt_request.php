<?php

defined('TPT_INIT') or die('access denied');

class tpt_request {

	static $redirect = false;
	static $redirect_url = '';
	static $redirects = array();

	function __construct(&$vars) {
	}

	static function base_404(&$vars, $client=0) {
		$urlrules_module = getModule($vars, "UrlRules");
		$urlrules = $urlrules_module->moduleData['id'];

		//$vars['environment']['page_rule'] = $urls[41];
		$vars['environment']['isRedirect'] = false;
		$vars['environment']['is404'] = 1;
		$vars['environment']['is414'] = 0;
		$vars['environment']['page_rule'] = $urlrules[$vars['config']['default']['urlrule'][404]['id']];
		$vars['environment']['client'] = $client;
		$vars['template_data']['hasLeftBar'] = true;
		$vars['template_data']['hasSocialBar'] = true;
		$vars['logic']['main_include_file'] = '404.php';
		$vars['template']['title'] = 'Error 404 - Page Not Found';
		$exec404 = true;
	}

	static function base_414(&$vars, $client=0) {
		$urlrules_module = getModule($vars, "UrlRules");
		$urlrules = $urlrules_module->moduleData['id'];

		//$vars['environment']['page_rule'] = $urls[41];
		$vars['environment']['isRedirect'] = false;
		$vars['environment']['is404'] = 0;
		$vars['environment']['is414'] = 1;
		$vars['environment']['page_rule'] = $urlrules[$vars['config']['default']['urlrule'][414]['id']];
		$vars['environment']['client'] = $client;
		$vars['template_data']['hasLeftBar'] = true;
		$vars['template_data']['hasSocialBar'] = true;
		$vars['logic']['main_include_file'] = '414.php';
		$vars['template']['title'] = 'Error 414 - Request too Long';
		$exec404 = true;
	}

	static function base_redirect(&$vars, $redirect_url) {
		$urlrules_module = getModule($vars, "UrlRules");
		$urlrules = $urlrules_module->moduleData['id'];

		//$url = $vars['url']['handler']->wrap($vars, $redirect_url);

		self::redirect($vars, $redirect_url);

		//tpt_dump($urlrules[41], true);
		//$vars['environment']['page_rule'] = $urlrules[174];
		$vars['environment']['isRedirect'] = true;
		$vars['environment']['is404'] = 0;
		$vars['environment']['is414'] = 0;
		$vars['template_data']['hasLeftBar'] = false;
		$vars['template_data']['hasSocialBar'] = false;
		$vars['logic']['main_include_file'] = 'redirect.php';
		$vars['template']['title'] = 'This page redirects to <a href="'.$redirect_url.'">'.$redirect_url.'</a>';
		$exec404 = false;
	}

	static function base_execute(&$vars, $urls) {
		$urlrules_module = getModule($vars, "UrlRules");
		$urlrules = $urlrules_module->moduleData['id'];

		//$url = $vars['url']['handler']->wrap($vars, $redirect_url);

		//tpt_dump($urlrules[41], true);
		//$vars['environment']['page_rule'] = $urlrules[174];
		//$vars['environment']['isAdministration'] = false;
		//$vars['environment']['is404'] = false;
		//$vars['template_data']['hasLeftBar'] = false;
		//$vars['logic']['main_include_file'] = 'redirect.php';
		//$vars['template']['title'] = 'This page redirects to <a href="'.$redirect_url.'">'.$redirect_url.'</a>';
		//$exec404 = false;

		$mrule = $urls['mrule'];
		//tpt_dump($mrule, true);

		if($status = !empty($mrule)) {
			//tpt_dump('asd', true);
			$vars['environment']['page_rule'] = $mrule;


			if(!$mrule['sustain_url'] && in_array($mrule['url_match_type'], array('urpath', 'rpath', 'rsegments'))) {
				$af_byreg = $mrule['url_preg_pattern'];
				$vars['url']['bpath'] = array_filter($vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
				$vars['url']['path'] = implode('/', $vars['url']['bpath']); // regenerate $vars['url']['path']
			}

			$vars['environment']['force404'] = $mrule['is_404'];
			$vars['environment']['force414'] = $mrule['is_414'];
			$vars['environment']['isAjax'] = $mrule['is_ajax'];
			//tpt_dump($mrule);
			$vars['template_data']['hasLeftBar'] = $mrule['left_bar'];
			$vars['template_data']['hasSocialBar'] = $mrule['social_bar'];
			if(empty($mrule['social_bar'])) {
				$vars['template']['css_class']['outer-wrapper'] = 'width-1000';
				$vars['template']['css_class']['content'] = 'width-788';
			}
			$vars['logic']['main_include_file'] = $mrule['include_file'];
			if(in_array($vars['user']['client_ip'], $vars['config']['urlrule_page_dev_file_ips']) && !empty($mrule['dev_include_file'])) {
				$vars['logic']['main_include_file'] = $mrule['dev_include_file'];
			} else {
				$vars['logic']['main_include_file'] = $mrule['include_file'];
			}

			if(!$mrule['is_ajax']) {
				$vars['template_data']['meta'][] = $mrule['html_meta_tags'];
				$vars['template']['title'] = $mrule['html_title'];
				if(!empty($mrule['html_head_content'])) {
					$vars['template_data']['head'][] = $mrule['html_head_content'];
				}
			} else {
				$vars['_temp']['rule_data'] = $mrule;
			}
		} else {
			if(!tpt_current_user::authorize_block($vars, 'TPT_ACCESS_BACKEND_404_PAGE')) {
				self::base_404($vars, 1);
			} else {
				self::base_404($vars);
			}

		}
	}


	static function redirect(&$vars, $href) {
		//die($href);
		self::$redirect = true;
		self::$redirect_url = $href;
		self::$redirects[] = $href;
		//file_put_contents('asd.txt', $href, FILE_APPEND);
		//var_dump($href);die();
	}

	static function setcookie(&$vars, $name, $value='', $time='', $path='') {
		if(empty($vars['config']['dev']['no_cookies'])) {
			setcookie($name, $value, $time, $path);
		}
	}

	function afterContent(&$vars) {
		//var_dump($vars['config']['https']);die();
		//tpt_dump('aaaaaaa');

		$log_redirect = 0;
		$log_url = '';

		if(!isDevAccess() || empty($vars['config']['dev']['disable_internal_redirects'])) {
			if($vars['user']['isLogged'] && !$vars['config']['https']) {
				if(self::$redirect) {
					$log_redirect = 1;
					$log_url = self::$redirect_url;
					//var_dump(self::$redirect_url);die();
					self::$redirect_url = $vars['url']['handler']->wrap($vars, self::$redirect_url, true);

					if(!isDevLog() || empty($vars['config']['dev']['early_output'])) {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.self::$redirect_url);
					}
					$vars['template_data']['footer_scripts']['scripts'][] = 'document.location.href = "'.self::$redirect_url.'";';
					$vars['template']['content'] = 'This page redirects to somewhere else...';
					//$vars['environment']['isAjax'] = true;
					//$vars['environment']['ajax_response'] = 'This page redirects to somewhere else...';
				} else {
					$log_redirect = 2;
					$log_url = REQUEST_URL_SECURE;
					//$return_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
					$return_url = REQUEST_URL_SECURE;
					//var_dump($return_url);die();
					//var_dump($return_url);die();
					self::$redirect_url = $vars['url']['handler']->wrap($vars, self::$redirect_url, true);
					if(!isDevLog() || empty($vars['config']['dev']['early_output'])) {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.$return_url);
					}
					$vars['template_data']['footer_scripts']['scripts'][] = 'document.location.href = "'.$return_url.'";';
					$vars['template']['content'] = 'This page redirects to <a href="'.self::$redirect_url.'">...';
				}
			} else {
				if(self::$redirect) {
					$log_redirect = 3;
					$log_url = self::$redirect_url;
					//var_dump(self::$redirect_url);die();
					if(strpos(self::$redirect_url, 'http') !== 0) {
						self::$redirect_url = $vars['url']['handler']->wrap($vars, self::$redirect_url, true);
					}
					if(!isDevLog() || empty($vars['config']['dev']['early_output'])) {
						header("HTTP/1.1 301 Moved Permanently");
						header('Location: '.self::$redirect_url);
					}

					$vars['template_data']['footer_scripts']['scripts'][] = 'document.location.href = "'.self::$redirect_url.'";';
					$vars['template']['content'] = 'This page redirects to <a href="'.self::$redirect_url.'"></a>...';
					//$vars['environment']['isAjax'] = true;
					//$vars['environment']['ajax_response'] = 'This page redirects to somewhere else...';
				}
			}
		} else {
			tpt_logger::dump($vars, self::$redirects, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'self::$redirects', __FILE__.' '.__LINE__);
		}

		if(!empty($log_redirect) && !empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_internal_redirects'])) {
			tpt_logger::log_redirect($vars, 'tpt_request_rq_redirect', $log_redirect, $log_url);
		}
		if(!empty($log_redirect) && isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_internal_redirects_dev'])) {
			tpt_logger::log_redirect($vars, 'tpt_request_rq_redirect_dev', $log_redirect, $log_url);
		}
	}
}

$tpt_vars['environment']['url_processors'][] = $tpt_vars['environment']['request']['handler'] = new tpt_request($tpt_vars);
