#!/usr/local/bin/php
<?php
define('TPT_INIT', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(0);
ini_set('memory_limit', '-1');

include_once(dirname(__FILE__) .  DIRECTORY_SEPARATOR . 'log-php-errors.php');
include_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'tpt_init-minimal.php');

$db = $tpt_vars['db']['handler'];
$fslogdb = DB_DB_FILE_SYSTEM_LOGS;

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


global $tpt_vars;


$stdin = fopen('php://stdin', 'r');
ob_implicit_flush(true);
while ($line = fgets($stdin)) {
	$logfile = dirname(__FILE__) . DIRECTORY_SEPARATOR . "audit_log_" . date("dmY") . ".txt";
	file_put_contents($logfile, $line, FILE_APPEND);
	$timestamp = time();
	$datetime = date('Y-m-d H:i:s O');

	$query = 'INSERT INTO
				`'.$fslogdb.'`.`tpt_log_audit`
			SET
				insert_php_timestamp=:insert_php_timestamp,
				insert_php_datetime=:insert_php_datetime,
				complete_data=:complete_data';

	$db->prepare($query);
	$db->bindParam(":insert_php_timestamp", $timestamp);
	$db->bindParam(":insert_php_datetime", $datetime);
	$db->bindParam(":complete_data", $line);
	$db->execute();

	print $line;
}
