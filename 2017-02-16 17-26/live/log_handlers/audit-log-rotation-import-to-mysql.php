<?php
// mysql doesn't seem to recreate the log files when they are deleted
//die('asd');
defined('TPT_INIT') or die('access denied');

$db = $tpt_vars['db']['handler'];
$fslogdb = DB_DB_FILE_SYSTEM_LOGS;
$zone = intval(date('O'), 10);
$date = date('Y-m-d_H-i-s').'_'.$zone;
$date2 = explode('-', date('Y-m-d'));
$year = $date2[0];
$month = $date2[1];
$day = $date2[2];

$cols = $db->show_columns('audit_log', $fslogdb);

//die('asd');
$handle = @fopen('/var/log/audit/audit.log', "r");
//tpt_dump('asdasdasd', true);
if ($handle) {
	while (($line = fgets($handle)) !== false) {
		$line = trim($line);
		if(!empty($line)) {
			$l = str_replace('old auid', 'old_auid', $line);
			$l = str_replace('new auid', 'new_auid', $l);
			$l = str_replace('old ses', 'old_ses', $l);
			$l = str_replace('new ses', 'new_ses', $l);
			if(preg_match('#(.*) msg=\'(.*)\'#', $l, $m)) {
				$e = str_replace(' ', '&', $m[1]);
				$event = array();
				parse_str($e, $event);
				$event['insert_php_timestamp'] = time();
				$event['insert_php_datetime'] = date('Y-m-d H:i:s O');
				$event['msg1'] = (isset($m['msg'])?$m['msg']:null);
				$msg2 = $m[2];
				$event['msg2'] = $msg2;
				$event['complete_data'] = $line;
				$msg2 = str_replace(' ', '&', $msg2);
				$m2 = array();
				parse_str($msg2, $m2);
				$m22 = array();
				foreach($m2 as $k=>$v) {
					$m22['msg2_'.$k] = $v;
				}
				$event += $m22;
				$event = array_intersect_key($event, $cols);
				$keys = array_keys($event);
				$fields = implode(',', array_map(function($a,$b){return $a.'=:'.$b;}, $keys, $keys));
				$query = '
INSERT INTO
`'.$fslogdb.'`.`audit_log`
SET
'.$fields.'
				';
				//var_dump('asd');
				//var_dump($query);die();
				$db->prepare($query);
				foreach($keys as $key) {
					$db->bindParam($key, $event[$key]);
					//var_dump($db->bindParam($key, $event[$key]));
				}
				$db->execute();
				/*
				var_dump($db->execute());die();
				try {
					$db->execute();
				} catch (PDOException $exception) {
					die($exception->getMessage());
				}
				*/
			} else {
				$e = str_replace(' ', '&', $l);
				$event = array();
				parse_str($e, $event);
				$event['insert_php_timestamp'] = time();
				$event['insert_php_datetime'] = date('Y-m-d H:i:s O');
				$event['msg1'] = (isset($m['msg'])?$m['msg']:null);
				$event['complete_data'] = $line;
				//var_dump($event);
				$event = array_intersect_key($event, $cols);
				//var_dump($event);
				$keys = array_keys($event);
				//var_dump($keys);
				$fields = implode(',', array_map(function($a,$b){return $a.'=:'.$b;}, $keys, $keys));
				//var_dump($fields);
				$query = '
INSERT INTO
`'.$fslogdb.'`.`audit_log`
SET
'.$fields.'
				';
				//var_dump('ddd');
				//var_dump($query);
				$db->prepare($query);
				foreach($keys as $key) {
					$db->bindParam($key, $event[$key]);
					//var_dump($key);
					//var_dump($event[$key]);
				}
				//var_dump($db->execute());die();
				//die();
				$db->execute();
				//var_dump($db->errorInfo());die();
			}
		}
	}

	if(!file_exists('/var/log/audit/archive/'.$year)) {
		mkdir('/var/log/audit/archive/'.$year);
	}
	if(!file_exists('/var/log/audit/archive/'.$year.'/'.$month)) {
		mkdir('/var/log/audit/archive/'.$year.'/'.$month);
	}
	if(!file_exists('/var/log/audit/archive/'.$year.'/'.$month.'/'.$day)) {
		mkdir('/var/log/audit/archive/'.$year.'/'.$month.'/'.$day);
	}
	copy('/var/log/audit/audit.log', '/var/log/audit/archive/'.$year.'/'.$month.'/'.$day.'/audit_'.$date.'.log');
	file_put_contents('/var/log/audit/audit.log', '');
}