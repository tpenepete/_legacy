#!/usr/bin/php
<?php
define('TPT_INIT', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dashNull($var) {
	if(preg_match('#[\D]#', $var)) {
		return null;
	}

	return $var;
}


function iVal($var, $base=10) {
	$var = preg_replace('#[\D]*#', '', $var);

	$base = intval($base, 10);
	return intval($var, $base);
}

function timezoneOffsetString($offset) {
	return sprintf("%s%02d%02d", ($offset >= 0) ? '+' : '-', abs($offset / 3600), abs($offset % 3600));
}


//global $tpt_vars;

$tables = array(
	1=>'apache_access_log_https',
	2=>'apache_access_log_http',
	3=>'apache_access_log_res_https',
	4=>'apache_access_log_res_http'
);

//$database = new Database();
//$db = $database->getConnection();
include_once(dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'log-php-errors.php');
include_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'tpt_init-minimal.php');
$db = $tpt_vars['db']['handler'];
$slogdb = DB_DB_APACHE_LOGS;

$script_timestamp = time();

$environment_time_zone = getenv("TZ"); //environment timezone
//if ($timeZone === false) {
$system_time_zone = exec('date +%Z'); //system timezone
$system_time_zone_offset = exec('date +%z'); //system timezone
//}
$dateTimeZone = new DateTimeZone(((!empty($environment_time_zone))?$environment_time_zone:$system_time_zone));
$offset = $dateTimeZone->getOffset(new DateTime(null, $dateTimeZone));
$derived_time_zone_offset = timezoneOffsetString($offset);

$stdin = fopen('php://stdin', 'r');
ob_implicit_flush(true);

/*
$timeZone = getenv("TZ"); //environment timezone
if ($timeZone === FALSE) {
	$timeZone = exec('date +%Z'); //system timezone
}
$dateTimeZone = new DateTimeZone($timeZone);
$offset = $dateTimeZone->getOffset(new DateTime(null, $dateTimeZone));
$systemTimeZone = timezoneOffsetString($offset);
*/

while ($line = fgets($stdin)) {
	$logfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "access_" . date("Ymd") . ".txt";
	file_put_contents($logfile, $line, FILE_APPEND);

	$php_timestamp = $time = time();
	$php_date_time = date('Y-m-d H:i:s', $php_timestamp);
	$php_time_zone_offset = date('O');
	$php_time_zone_identifier = date('e');

	if(empty($db->PDOexception)) {
		list($logType, $unique_id, $ipAddress, $logname, $user, $tDateTime, $request, $status, $size, $referrer, $user_agent, $cookie, $setcookie, $time_taken) = explode('/|\\', $line);

		list($d, $M, $y, $h, $m, $s, $z) = sscanf(substr($tDateTime, 0, 28), "[%2d/%3s/%4d:%2d:%2d:%2d %5s]");
		$serverTimestamp = strtotime("$d $M $y $h:$m:$s $z");
		$dateTime = date('Y-m-d H:i:s', $serverTimestamp);

		$size = dashNull($size);
		$time_taken = dashNull($time_taken);
		$request_hash = sha1($request);
		$referrer_hash = sha1($referrer);
		$user_agent_hash = sha1($user_agent);

		if (empty($logType) || empty($tables[$logType])) {
			//print $line;
			//continue;
		} else {

			$tableName = $tables[$logType];

			$query = "
INSERT INTO
	`{$slogdb}`.`{$tableName}`
SET
	`{$slogdb}`.`{$tableName}`.`script_timestamp`=:script_timestamp,
	`{$slogdb}`.`{$tableName}`.`php_timestamp`=:php_timestamp,
	`{$slogdb}`.`{$tableName}`.`php_date_time`=:php_date_time,
	`{$slogdb}`.`{$tableName}`.`php_time_zone_offset`=:php_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`php_time_zone_identifier`=:php_time_zone_identifier,
	`{$slogdb}`.`{$tableName}`.`environment_time_zone`=:environment_time_zone,
	`{$slogdb}`.`{$tableName}`.`system_time_zone`=:system_time_zone,
	`{$slogdb}`.`{$tableName}`.`system_time_zone_offset`=:system_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`derived_time_zone_offset`=:derived_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`unique_id`=:unique_id,
	`{$slogdb}`.`{$tableName}`.`timestamp_server`=:timestamp_server,
	`{$slogdb}`.`{$tableName}`.`date_time`=:date_time,
	`{$slogdb}`.`{$tableName}`.`timezone`=:timezone,
	`{$slogdb}`.`{$tableName}`.`timestamp`=:timestamp,
	`{$slogdb}`.`{$tableName}`.`time_taken`=:time_taken,
	`{$slogdb}`.`{$tableName}`.`ip_address`=:ip_address,
	`{$slogdb}`.`{$tableName}`.`log_name`=:log_name,
	`{$slogdb}`.`{$tableName}`.`user`=:user,
	`{$slogdb}`.`{$tableName}`.`request`=:request,
	`{$slogdb}`.`{$tableName}`.`status`=:status,
	`{$slogdb}`.`{$tableName}`.`size`=:size,
	`{$slogdb}`.`{$tableName}`.`referrer`=:referrer,
	`{$slogdb}`.`{$tableName}`.`user_agent`=:user_agent,
	`{$slogdb}`.`{$tableName}`.`cookie`=:cookie,
	`{$slogdb}`.`{$tableName}`.`setcookie`=:setcookie,
	`{$slogdb}`.`{$tableName}`.`request_hash`=:request_hash,
	`{$slogdb}`.`{$tableName}`.`referrer_hash`=:referrer_hash,
	`{$slogdb}`.`{$tableName}`.`user_agent_hash`=:user_agent_hash,
	`{$slogdb}`.`{$tableName}`.`complete_data`=:complete_data
";

			if (!$db->prepare($query)) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "access_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.') '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
			}
			$db->bindParam(":script_timestamp", $script_timestamp);
			$db->bindParam(":php_timestamp", $php_timestamp);
			$db->bindParam(":php_date_time", $php_date_time);
			$db->bindParam(":php_time_zone_offset", $php_time_zone_offset);
			$db->bindParam(":php_time_zone_identifier", $php_time_zone_identifier);
			$db->bindParam(":environment_time_zone", $environment_time_zone);
			$db->bindParam(":system_time_zone", $system_time_zone);
			$db->bindParam(":system_time_zone_offset", $system_time_zone_offset);
			$db->bindParam(":derived_time_zone_offset", $derived_time_zone_offset);
			$db->bindParam(":unique_id", $unique_id);
			$db->bindParam(":timestamp_server", $serverTimestamp);
			$db->bindParam(":timestamp", $time);
			$db->bindParam(":date_time", $dateTime);
			$db->bindParam(":timezone", $z);
			$db->bindParam(":time_taken", $time_taken);
			$db->bindParam(":ip_address", $ipAddress);
			$db->bindParam(":log_name", $logname);
			$db->bindParam(":user", $user);
			$db->bindParam(":request", $request);
			$db->bindParam(":status", $status);
			$db->bindParam(":size", $size);
			$db->bindParam(":referrer", $referrer);
			$db->bindParam(":user_agent", $user_agent);
			$db->bindParam(":cookie", $cookie);
			$db->bindParam(":setcookie", $setcookie);
			$db->bindParam(":request_hash", $request_hash);
			$db->bindParam(":referrer_hash", $referrer_hash);
			$db->bindParam(":user_agent_hash", $user_agent_hash);
			$db->bindParam(":complete_data", $line);
			if (!$db->execute()) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "access_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.') '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
			}

		}
	} else {
		$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "access_db_" . date("Ymd") . ".txt";
		file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.') '.$db->PDOexception->getMessage().' '.$db->errorCodeConnection().' '.var_export($db->errorInfoConnection(), true)."\n", FILE_APPEND);
	}

	print $line;
}

fclose($stdin);
