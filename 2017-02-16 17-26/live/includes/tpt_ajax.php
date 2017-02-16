<?php
defined('TPT_INIT') or die('access denied');

class tpt_ajax {
	static $calls = array();

	function __construct(&$vars) {
		//tpt_dump($vars['data'], true);
		//tpt_dump($_SESSION['userid'], true);
		$query = 'SELECT * FROM `'.DB_DB.'`.`tpt_module_users` WHERE `id`='.((isset($vars['user']['userid'])&&!empty($vars['user']['userid']))?$vars['user']['userid']:(isset($_SESSION['userid'])?$_SESSION['userid']:0));
		$vars['db']['handler']->prepare($query);
		$vars['db']['handler']->execute();
		$user = $vars['db']['handler']->fetch();
		$access_level = 0;
		if(!empty($user)) {
			$access_level = $user['access_level'];
		}

		//$caccess = !empty($vars['user']['data']['access_level'])?intval($vars['user']['data']['access_level'], 10):0;
		$query = 'SELECT * FROM `'.DB_DB.'`.`tpt_ajax_calls` WHERE `enabled`=1 AND `access_level`<='.$access_level.'';
		$vars['db']['handler']->query($query);
		$vars['data']['tpt_ajax_calls']['task'] = $vars['db']['handler']->fetch_assoc_list('task', false);

		//$vars['data']['tpt_ajax_calls']['task'] += $vars['data']['tpt_ajax_calls_admin']['task'];

		//self::$calls = $vars['data']['tpt_ajax_calls']['task'] + $vars['data']['tpt_ajax_calls_admin']['task'];
		self::$calls = $vars['data']['tpt_ajax_calls']['task'];
	}

	static function getCall($task) {
		return 'goGetSome(\''.$task.'\', '.self::$calls[$task]['arg'].');';
	}

	function beforeContent(&$vars) {
		/*
		if(defined('TPT_ADMIN')) {

			if(!empty($vars['data']['tpt_ajax_calls_admin']['task'])) {
				$vars['data']['tpt_ajax_calls']['task'] += $vars['data']['tpt_ajax_calls_admin']['task'];
				//tpt_dump($vars['data']['tpt_ajax_calls']['task'], true);
			}
		}
		*/

		$query = 'SELECT * FROM `'.DB_DB.'`.`tpt_module_users` WHERE `id`='.((isset($vars['user']['userid'])&&!empty($vars['user']['userid']))?$vars['user']['userid']:0);
		$vars['db']['handler']->prepare($query);
		$vars['db']['handler']->execute();
		$user = $vars['db']['handler']->fetch();
		$access_level = 0;
		if(!empty($user)) {
			$access_level = $user['access_level'];
		}

		$query = 'SELECT * FROM `'.DB_DB.'`.`tpt_ajax_calls` WHERE `enabled`=1 AND `access_level`<='.$access_level.'';
		$vars['db']['handler']->query($query);
		$vars['data']['tpt_ajax_calls']['task'] = $vars['db']['handler']->fetch_assoc_list('task', false);

		$query = 'SELECT * FROM `'.DB_DB.'`.`tpt_ajax_calls_admin` WHERE `enabled`=1 AND `access_level`<='.$access_level.'';
		$vars['db']['handler']->query($query);
		$vars['data']['tpt_ajax_calls_admin']['task'] = $vars['db']['handler']->fetch_assoc_list('task', false);

		//self::$calls = $vars['data']['tpt_ajax_calls_admin']['task'];
		self::$calls = $vars['data']['tpt_ajax_calls']['task'] + $vars['data']['tpt_ajax_calls_admin']['task'];
	}

	function afterContent(&$vars) {
		//tpt_dump($vars['data']['tpt_ajax_calls']['task'], true);
	}
}


$tpt_vars['environment']['url_processors'][] = $tpt_vars['ajax'] = new tpt_ajax($tpt_vars);
//$tpt_vars['ajax']::$calls = $tpt_vars['data']['tpt_ajax_calls']['task'];