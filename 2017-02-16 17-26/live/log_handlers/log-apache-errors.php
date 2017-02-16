#!/usr/bin/php
<?php
define('TPT_INIT', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//file_put_content('/home/amazingw/public_html/live/log_handlers/asd.txt', 'asd', FILE_APPEND);
//require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'database.php');

function timezoneOffsetString($offset) {
    return sprintf("%s%02d%02d", ($offset >= 0) ? '+' : '-', abs($offset / 3600), abs($offset % 3600));
}

function getValueFromString($logString) {
    $stringValue = '';
    if (!empty($logString)) {
        $parts = explode(' ', $logString, 2);
        $stringValue = (isset($parts[1]) ? rtrim($parts[1], ']') : '');
    }
    return $stringValue;
}


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
//echo $stdin;
ob_implicit_flush(true);

while ($line = fgets($stdin)) {
	$logfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_" . date("Ymd") . ".txt";
	file_put_contents($logfile, $line, FILE_APPEND);

	$php_timestamp = time();
	$php_date_time = date('Y-m-d H:i:s', $php_timestamp);
	$php_time_zone_offset = date('O');
	$php_time_zone_identifier = date('e');

	if (empty($db->PDOexception)) {
		//if(empty($logType) || empty($tables[$logType])) {
		//	continue;
		//}
		//$tableName = $tables[$logType];
		$tableName = 'apache_error_log';

		if (preg_match('#\[source: main\]#', $line)) {
			list($tSource, $tUniqueId, $tDateTime, $tErrorLevel, $tPid, $tModule, $tSourceFileName, $tErrorStatusCode, $tClientIpAddress, $tActualMessage, $tVirtualhost, $tReferrer, $tUserAgent) = explode(' %% ', $line);
			$source = getValueFromString($tSource);
			$unique_id = getValueFromString($tUniqueId);
			$tDateTime = getValueFromString($tDateTime);
			$serverTimestamp = null;
			if (!empty($tDateTime)) {
				$serverTimestamp = strtotime($tDateTime);
				//$dateTime = date('Y-m-d H:i:s', $errorTimestamp);
				//$serverTimestamp = $errorTimestamp;
				//$serverTimestamp = strtotime(date("Y-m-d", $errorTimestamp).'T'.date("H:i:s", $errorTimestamp).'-05:00');
			}
			$dateTime = date('Y-m-d H:i:s');
			if (!empty($tDateTime)) {
				$errorTimestamp = strtotime($tDateTime);
				$dateTime = date('Y-m-d H:i:s', $errorTimestamp);
				$serverTimestamp = $errorTimestamp;
				//$serverTimestamp = strtotime(date("Y-m-d", $errorTimestamp).'T'.date("H:i:s", $errorTimestamp).'-05:00');
			}
			$dt = $tDateTime . ' ' . $derived_time_zone_offset;

			$errorLevel = getValueFromString($tErrorLevel);
			$pid = getValueFromString($tPid);
			$module = getValueFromString($tModule);
			$sourceFileName = getValueFromString($tSourceFileName);
			$errorStatusCode = getValueFromString($tErrorStatusCode);
			$clientIpAddress = getValueFromString($tClientIpAddress);
			$actualMessage = getValueFromString($tActualMessage);
			$virtualhost = getValueFromString($tVirtualhost);
			$referrer = getValueFromString($tReferrer);
			$user_agent = getValueFromString($tUserAgent);


			$query = <<< EOT
INSERT INTO
	`{$slogdb}`.`{$tableName}`
SET
	`{$slogdb}`.`{$tableName}`.`script_timestamp`=:script_timestamp,
	`{$slogdb}`.`{$tableName}`.`php_timestamp`=:php_timestamp,
	`{$slogdb}`.`{$tableName}`.`php_date_time`=:php_date_time,
	`{$slogdb}`.`{$tableName}`.`php_time_zone_offset`=:php_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`php_time_zone_identifier`=:php_time_zone_identifier,
	`{$slogdb}`.`{$tableName}`.`timestamp_server`=:timestamp_server,
	`{$slogdb}`.`{$tableName}`.`date_time`=:date_time,
	`{$slogdb}`.`{$tableName}`.`environment_time_zone`=:environment_time_zone,
	`{$slogdb}`.`{$tableName}`.`system_time_zone`=:system_time_zone,
	`{$slogdb}`.`{$tableName}`.`system_time_zone_offset`=:system_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`derived_time_zone_offset`=:derived_time_zone_offset,
	`{$slogdb}`.`{$tableName}`.`timestamp`=:timestamp,
	`{$slogdb}`.`{$tableName}`.`source`=:source,
	`{$slogdb}`.`{$tableName}`.`unique_id`=:unique_id,
	`{$slogdb}`.`{$tableName}`.`dt`=:dt,
	`{$slogdb}`.`{$tableName}`.`error_level`=:error_level,
	`{$slogdb}`.`{$tableName}`.`pid`=:pid,
	`{$slogdb}`.`{$tableName}`.`module`=:module,
	`{$slogdb}`.`{$tableName}`.`source_file_name`=:source_file_name,
	`{$slogdb}`.`{$tableName}`.`status_code`=:status_code,
	`{$slogdb}`.`{$tableName}`.`client`=:client,
	`{$slogdb}`.`{$tableName}`.`actual_message`=:actual_message,
	`{$slogdb}`.`{$tableName}`.`virtualhost`=:virtualhost,
	`{$slogdb}`.`{$tableName}`.`referrer`=:referrer,
	`{$slogdb}`.`{$tableName}`.`user_agent`=:user_agent,
	`{$slogdb}`.`{$tableName}`.`complete_data`=:complete_data
EOT;


			if (!$db->prepare($query)) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.'):'.__LINE__.' '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
			}
			$db->bindParam(":script_timestamp", $script_timestamp);
			$db->bindParam(":php_timestamp", $php_timestamp);
			$db->bindParam(":php_date_time", $php_date_time);
			$db->bindParam(":php_time_zone_offset", $php_time_zone_offset);
			$db->bindParam(":php_time_zone_identifier", $php_time_zone_identifier);
			$db->bindParam(":timestamp_server", $serverTimestamp);
			$db->bindParam(":date_time", $dateTime);
			$db->bindParam(":environment_time_zone", $environment_time_zone);
			$db->bindParam(":system_time_zone", $system_time_zone);
			$db->bindParam(":system_time_zone_offset", $system_time_zone_offset);
			$db->bindParam(":derived_time_zone_offset", $derived_time_zone_offset);
			$db->bindParam(":timestamp", $time);
			$db->bindParam(":source", $source);
			$db->bindParam(":unique_id", $unique_id);
			$db->bindParam(":dt", $dt);
			$db->bindParam(":error_level", $errorLevel);
			$db->bindParam(":pid", $pid);
			$db->bindParam(":module", $module);
			$db->bindParam(":source_file_name", $sourceFileName);
			$db->bindParam(":status_code", $errorStatusCode);
			$db->bindParam(":client", $clientIpAddress);
			$db->bindParam(":actual_message", $actualMessage);
			$db->bindParam(":virtualhost", $virtualhost);
			$db->bindParam(":referrer", $referrer);
			$db->bindParam(":user_agent", $user_agent);
			$db->bindParam(":complete_data", $line);
			if (!$db->execute()) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.'):'.__LINE__.' '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
			}
		} else {
			//$dateTime = date('Y-m-d H:i:s', strtotime(trim($tDateTime)));

			/*
			  $query = <<< EOT
			  INSERT INTO
			  {$tableName}
			  SET
			  timestamp=:timestamp,
			  date_time=:date_time,
			  source=:source,
			  unique_id=:unique_id,
			  dt=:dt,
			  error_level=:error_level,
			  pid=:pid,
			  source_file_name=:source_file_name,
			  status_code=:status_code,
			  client=:client,
			  actual_message=:actual_message,
			  referrer=:referrer,
			  user_agent=:user_agent,
			  complete_data=:complete_data
			  EOT;
			 */
			$query = <<< EOT
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
	`{$slogdb}`.`{$tableName}`.`timestamp`=:timestamp,
	`{$slogdb}`.`{$tableName}`.`complete_data`=:complete_data
EOT;
			if (!$db->prepare($query)) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.'):'.__LINE__.' '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
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
			$db->bindParam(":timestamp", $time);
			//$db->bindParam(":date_time", $dateTime);
			//$db->bindParam(":source", $source);
			//$db->bindParam(":unique_id", $unique_id);
			//$db->bindParam(":dt", $tDateTime);
			//$db->bindParam(":error_level", $errorLevel);
			//$db->bindParam(":pid", $pid);
			//$db->bindParam(":module", $module);
			//$db->bindParam(":source_file_name", $sourceFileName);
			//$db->bindParam(":status_code", $errorStatusCode);
			//$db->bindParam(":client", $clientIpAddress);
			//$db->bindParam(":actual_message", $actualMessage);
			//$db->bindParam(":virtualhost", $virtualhost);
			//$db->bindParam(":referrer", $referrer);
			//$db->bindParam(":user_agent", $user_agent);
			$db->bindParam(":complete_data", $line);

			if (!$db->execute()) {
				$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_db_" . date("Ymd") . ".txt";
				file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.'):'.__LINE__.' '.var_export($db->errorInfo(), true)."\n", FILE_APPEND);
			}
		}
	} else {
		$dblogfile = '/var/log/httpd/user/customizer' . DIRECTORY_SEPARATOR . "errors_db_" . date("Ymd") . ".txt";
		file_put_contents($dblogfile, '('.$php_date_time.' '.$php_time_zone_offset.'):'.__LINE__.' '.$db->PDOexception->getMessage().' '.$db->errorCodeConnection().' '.var_export($db->errorInfoConnection(), true)."\n", FILE_APPEND);
	}

    print $line;
}

fclose($stdin);