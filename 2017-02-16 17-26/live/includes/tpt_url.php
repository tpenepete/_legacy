<?php

defined('TPT_INIT') or die('access denied');

class tpt_url
{

	function __construct(&$vars) {
	}


	function wrap(&$vars, $href, $secure = false, $client = 0) {
		$base_url = $vars['config']['baseurl'];
		if(!empty($client)) {
			$base_url = BASE_URL.'/'.$vars['config']['dev']['allypass'];
		}

		if (empty($href)) {
			return $base_url;
		}

		$urlparts = explode('?', $href);
		$h = $urlparts[0];
		$query = (!empty($urlparts[1]) ? '?' . $urlparts[1] : '');

		if (isset($vars['template_data']['links'][$h])) {
			$h = $vars['template_data']['links'][$h];
		}

		if (strstr($h, 'javascript:') !== 0) {
			$h = htmlspecialchars_decode($h);
			if (strstr($h, $base_url) === false) {
				if ($h[0] != '/')
					$h = '/' . $h;
				$h = $base_url . $h;
			}
		}

		if ($secure && (strpos($h, 'https://') !== 0)) {
			$h = preg_replace('#^(http://)?www\.(.*)#', 'https://www.$2', $h);
		}

		$h .= $query;

//if($_SERVER['REMOTE_ADDR'] == '85.130.3.155') {
//    echo '<pre>';
//    var_dump($vars['template_data']['links']);
//    var_dump($href);
//    var_dump($h);
//    echo '</pre>';
//}

		return $h;
	}





}

$tpt_vars['url']['handler'] = new tpt_url($tpt_vars);
