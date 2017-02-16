<?php


//var_dump(phpversion());

/*
define('cache_dir',TPT_CACHE_DIR.DS.'pages');

class tpt_content_cache {
	
	static $v;
	static $db;
	
	static function rq_eval(&$vars) {
		
		self::$v = &$vars;
		self::$db = &$vars['db']['handler'];
		
	}
	
	static function handle_cache() {

//		var_dump(self::$v['environment']['page_rule']);
		
		if ($_SERVER['REQUEST_METHOD']!='GET') return;
		if (!empty($_GET)) return;
		if (@self::$v['environment']['page_rule']['cacheable']==0) return;
		
		var_dump($_SERVER['REQUEST_URI']);
		var_dump(hash('crc32',$_SERVER['REQUEST_URI']));
		
		
		
	}
	
	
}

tpt_content_cache::rq_eval($tpt_vars);

*/


$tpt_vars['cache'] = array();
$tpt_vars['cache']['content'] = array();

//$tpt_vars['cache']['content']['cacheable'] = 0;
$tpt_vars['cache']['content']['head'] = '';
$tpt_vars['cache']['content']['content'] = '';
$tpt_vars['cache']['content']['social_bar'] = '';
$tpt_vars['cache']['content']['footer_code'] = '';


class tpt_content_cache {

	static function getContent(&$vars) {
		//tpt_dump(hash('crc32',$vars['config']['requesturl']));
		$c = (in_array($vars['user']['client_ip'], is_array($vars['config']['dev_console_ips']) ? $vars['config']['dev_console_ips'] : array()));
		if(!empty($vars['config']['cache']['enabled']) && (!isDev('uncachecontent_get') || empty($_GET['uncachecontent'])) && !isDev('uncachecontent') && empty($c) && !empty($vars['environment']['page_rule']['cache_enabled']) && empty($_GET['gclid']) && empty($_GET['utm_source']) && ($vars['environment']['request_method'] != 'post') && ((!isDev('rebuildcontent') || empty($_GET['rebuildcontent'])))) {
			$url = mysql_real_escape_string($vars['config']['requesturl']);
			$url_id = intval($vars['environment']['page_rule']['id'], 10);
			$mobile = intval($vars['environment']['mobile_template'], 10);

			$cache_id = intval($vars['environment']['page_rule']['tpt_cache_content_id'.$mobile], 10);
			//tpt_dump($url);
			//$tpt_vars['cache']['content']['content']
			$c = array();
			if(empty($_GET)) {
				$c = $vars['db']['handler']->getData($vars, 'tpt_cache_content', '`head`,`content`,`social_bar`,`footer_code`', '`id`='.$cache_id);
			} else {
				$c = $vars['db']['handler']->getData($vars, 'tpt_cache_content', '`head`,`content`,`social_bar`,`footer_code`', '`url_id`='.$url_id.' AND `mobile`='.$mobile.' AND `url`="'.$url.'"');
			}


			if(!empty($c)) {
				$c = reset($c);
				$vars['cache']['content']['head'] = $c['head'];
				$vars['cache']['content']['content'] = $c['content'];
				$vars['cache']['content']['social_bar'] = $c['social_bar'];
				$vars['cache']['content']['footer_code'] = $c['footer_code'];
			}
			//return $vars['config']['requesturl'];
		}

		//tpt_dump(strlen($vars['cache']['content']['content']));

	}



	static function storeContent(&$vars) {
		//tpt_dump(array_keys($vars['modules']['handler']->modules));

		//tpt_dump($vars['cache']['content']['content'], true);

		//tpt_dump((!isDev('uncachecontent_get') || empty($_GET['uncachecontent'])));
		//tpt_dump(!isDev('uncachecontent'));
		//tpt_dump(empty($c));
		//tpt_dump(($vars['environment']['request_method'] != 'post'));
		//tpt_dump(empty($_GET['gclid']) && empty($_GET['utm_source']));
		//tpt_dump(((isDev('rebuildcontent') && !empty($_GET['rebuildcontent'])) || (!empty($vars['environment']['page_rule']['cache_enabled']) && !empty($vars['template']['content']) && empty($vars['cache']['content']['content']))), true);

		$c = (in_array($vars['user']['client_ip'], is_array($vars['config']['dev_console_ips']) ? $vars['config']['dev_console_ips'] : array()));
		if(!empty($vars['config']['cache']['enabled']) && (!isDev('uncachecontent_get') || empty($_GET['uncachecontent'])) &&
			!isDev('uncachecontent') &&
			empty($c) &&
			($vars['environment']['request_method'] != 'post') &&
			empty($_GET['gclid']) &&
			empty($_GET['utm_source']) /*&& empty($_GET)*/ &&
			(
					(isDev('rebuildcontent') && !empty($_GET['rebuildcontent'])) ||
					(!empty($vars['environment']['page_rule']['cache_enabled']) && !empty($vars['template']['content']) && empty($vars['cache']['content']['content']))
			)) {
			//tpt_dump($c, true);
			$query = $vars['config']['requesturlquery'];
			parse_str($query, $query);
			$purl = tpt_parse_url($vars['config']['requesturl']);
			if(((isDev('rebuildcontent') && !empty($_GET['rebuildcontent'])))) {
				//tpt_dump($vars['config']['requesturl']);
				$purl = remove_url_query_parameter($purl, 'rebuildcontent');
				//tpt_dump($url);
				//http_parse_params();
			}
			$url = tpt_build_url($purl);
			//tpt_dump($vars['cache']['content']['content'], true);

			$url = mysql_real_escape_string($url);
			$url_id = intval($vars['environment']['page_rule']['id'], 10);
			$url_hash = mysql_real_escape_string(hash('crc32', $vars['config']['requesturl']));
			$head = mysql_real_escape_string(tpt_html::sanitize_html(implode("\n", $vars['template_data']['head'])));
			//tpt_dump($head, true);
			//tpt_dump($vars['template_data']['head'], true);
			$content = mysql_real_escape_string(tpt_html::sanitize_html($vars['template']['content']));
			$social_bar = mysql_real_escape_string(tpt_html::sanitize_html($vars['template']['social_bar']));
			$footer_code = mysql_real_escape_string(tpt_html::sanitize_html($vars['template']['footer_code']));
			$mobile = intval($vars['environment']['mobile_template'], 10);
			$plain = intval(empty($purl['query']), 10);
			//tpt_dump($plain, true);

			$c = $vars['db']['handler']->getData($vars, 'tpt_cache_content', 'id', '`url_id`='.$url_id.' AND `url`="'.$url.'" AND `mobile`='.$mobile);

			if (!empty($c)) {
				$c = reset($c);
				$cache_id = $c['id'];
				$q = <<< EOT
UPDATE
	`tpt_cache_content`
SET
	`url_hash`="$url_hash",
	`head`="$head",
	`content`="$content",
	`social_bar`="$social_bar",
	`footer_code`="$footer_code"
WHERE
	`id`=$cache_id
EOT;
				//tpt_dump($head, true);
				//tpt_dump($q, true);
				$vars['db']['handler']->query($q);

				if(!empty($plain)) {
					$q = <<< EOT
UPDATE
	`tpt_module_urlrules`
SET
	`tpt_cache_content_id$mobile`=$cache_id
WHERE
	`id`=$url_id
EOT;
					$vars['db']['handler']->query($q);
				}
			} else {


				$q = <<< EOT
INSERT INTO
	`tpt_cache_content`
(
	`url`,
	`plain`,
	`url_id`,
	`url_hash`,
	`head`,
	`content`,
	`social_bar`,
	`footer_code`,
	`mobile`
)
VALUES(
	"$url",
	$plain,
	$url_id,
	"$url_hash",
	"$head",
	"$content",
	"$social_bar",
	"$footer_code",
	$mobile
)
EOT;
				$vars['db']['handler']->query($q);

				if(!empty($plain)) {
					$tpt_cache_content_id = $vars['db']['handler']->last_id();
					$q = <<< EOT
UPDATE
	`tpt_module_urlrules`
SET
	`tpt_cache_content_id$mobile`=$tpt_cache_content_id
WHERE
	`id`=$url_id
EOT;
					$vars['db']['handler']->query($q);
				}

			}
		}

	}

}