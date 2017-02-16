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
$nlogdb = DB_DB_NETWORK_LOGS;


function ipToCountry($ipAddress) {
    $numbers = explode(".", $ipAddress);
    $ranges = array();
    $country = "unknown";
    require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "ip_files/" . $numbers[0] . ".php");
    $code = ($numbers[0] * 16777216) + ($numbers[1] * 65536) + ($numbers[2] * 256) + ($numbers[3]);
    foreach ($ranges as $key => $value) {
        if ($key <= $code) {
            if ($ranges[$key][0] >= $code) {
                $country = $ranges[$key][1];
                break;
            }
        }
    }
    return $country;
}


$cols = $db->show_columns('network_log', $nlogdb);

$pipe = fopen('/var/log/syslog/messages.pipe', 'r');
//ob_implicit_flush(true);
ob_flush();


while ($line = fgets($pipe)) {
	$zone = intval(date('O'), 10);
	$date = date('Y-m-d_H-i-s').'_'.$zone;
	$date2 = explode('-', date('Y-m-d'));
	$year = $date2[0];
	$month = $date2[1];
	$day = $date2[2];

	$fields = array();
	if(preg_match('#([a-z]{3} [0-9]{1,2} [0-9]{2}:[0-9]{2}:[0-9]{2}) (host) (kernel:) \[[0-9]+?\.[0-9]+?\] (Firewall:) (\*[a-z]*?\*) IN=([a-z0-9]*?) OUT=([a-z0-9]*?) MAC=([a-z0-9:]*?) SRC=([a-z0-9\.]*?) DST=([a-z0-9\.]*?) LEN=([0-9]*?)#i', $line, $m)) {
		$fields['date_time'] = $m[1];
		$fields['host'] = $m[2];
		$fields['kernel'] = $m[3];
		$fields['xval'] = $m[4];
		$fields['firewall'] = $m[5];
		$fields['action'] = $m[6];
		$fields['IN'] = $m[7];
		$fields['OUT'] = $m[8];
		$fields['MAC'] = $m[9];
		$fields['SRC'] = $m[10];
		$fields['DST'] = $m[11];
		$fields['LEN'] = $m[12];
	} else if(preg_match('#([a-z]{3} [0-9]{1,2} [0-9]{2}:[0-9]{2}:[0-9]{2}) (host) (kernel:) (\[[0-9]+?\.[0-9]+?\]) (Firewall:) (\*[a-z]*?\*) IN=([a-z0-9]*?) OUT=([a-z0-9]*?) MAC=([a-z0-9:]*?) SRC=([a-z0-9\.]*?) DST=([a-z0-9\.]*?) WINDOW=([a-z0-9]*?) RES=([a-z0-9]*?) (SYN) UGRP=([a-z0-9]*?)#i', $line, $m)) {
		$fields['date_time'] = $m[1];
		$fields['host'] = $m[2];
		$fields['kernel'] = $m[3];
		$fields['xval'] = $m[4];
		$fields['firewall'] = $m[5];
		$fields['action'] = $m[6];
		$fields['IN'] = $m[7];
		$fields['OUT'] = $m[8];
		$fields['MAC'] = $m[9];
		$fields['SRC'] = $m[10];
		$fields['DST'] = $m[11];
		$fields['WINDOW'] = $m[12];
		$fields['RES'] = $m[13];
		$fields['SYN'] = $m[14];
		$fields['URGP'] = $m[15];
	}
	preg_match('#.*?SRC=([\S]+) .*?#', $line, $m);
	$ipAddress = (isset($m[1])?$m[1]:'');
	$timestamp = time();
	$dateTime = date('Y-m-d H:i:s', $timestamp);
	$timezone = date('O');

	$query = <<< EOT
INSERT INTO
	`$nlogdb`.`network_log`
SET
	timestamp=:timestamp,
	date_time=:date_time,
	timezone=:timezone,
	SRC=:SRC,
	raw_data=:raw_data
EOT;
	$db->prepare($query);
	$db->bindParam('timestamp', $timestamp);
	$db->bindParam('date_time', $dateTime);
	$db->bindParam('timezone', $timezone);
	$db->bindParam('SRC', $ipAddress);
	$db->bindParam('raw_data', $line);
	$db->execute();

    if(!empty($ipAddress)) {
		$country = ipToCountry($ipAddress);
        if ((($country == 'VN'))) {
            $networkLogId = $db->lastInsertId();

            $query = <<< EOT
INSERT INTO
	`$nlogdb`.`network_debug_log`
SET
	timestamp=:timestamp,
	date_time=:date_time,
	timezone=:timezone,
	network_log_id=:network_log_id,
	country=:country,
	ip=:ip,
	data=:data
EOT;
            $db->prepare($query);
            $db->bindParam('timestamp', $timestamp);
            $db->bindParam('date_time', $dateTime);
            $db->bindParam('timezone', $timezone);
            $db->bindParam('network_log_id', $networkLogId);
            $db->bindParam('country', $country);
            $db->bindParam('ip', $ipAddress);
            $db->bindParam('data', $line);
            $db->execute();
        }
    }
}


fclose($pipe);