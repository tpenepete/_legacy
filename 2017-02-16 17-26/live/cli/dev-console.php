<?php

defined('TPT_INIT') or die('access denied');

/*
$i = 0;
$handle = @fopen(TPT_BASE_DIR.DIRECTORY_SEPARATOR.'amazingwristbands.com-ssl_log-Mar-2016', "r");
//tpt_dump('asdasdasd', true);
if ($handle) {
	while (($buffer = fgets($handle, 65535)) !== false) {
		//echo $buffer;
		preg_match('#(.*?) (-.*?-) (\\[.*?\\]) "(.*?)( (?:.*?)( (?:.*?))?)?" (.*?) (.*?) (.*?) (.*)#', $buffer, $m);
		//tpt_dump($m, true);

		if(empty($m[1])) {
			//tpt_dump($item);
		}
		if(empty($m[3])) {
			//tpt_dump($item);
		}
		if(empty($m[4])) {
			//tpt_dump($item);
		}
		if(empty($m[5])) {
			//tpt_dump($item);
		}
		if(empty($m[6])) {
			//tpt_dump($item);
		}
		if(empty($m[7])) {
			//tpt_dump($item);
		}
		if(empty($m[8])) {
			//tpt_dump($item);
		}
		if(empty($m[9])) {
			//tpt_dump($item);
		}
		if(empty($m[10])) {
			//tpt_dump($item);
		}

		$query = '
INSERT INTO
	`tpt_request_rq_apache_access_amz_com_https_03_2016`
(
	`ip`,
	`timestamp`,
	`method`,
	`url`,
	`protocol`,
	`status`,
	`data_length`,
	`referer`,
	`user_agent`
)
VALUES(
	"'.mysql_real_escape_string($m[1]).'",
	"'.mysql_real_escape_string($m[3]).'",
	"'.mysql_real_escape_string($m[4]).'",
	"'.mysql_real_escape_string($m[5]).'",
	"'.mysql_real_escape_string($m[6]).'",
	"'.mysql_real_escape_string($m[7]).'",
	"'.mysql_real_escape_string($m[8]).'",
	"'.mysql_real_escape_string($m[9]).'",
	"'.mysql_real_escape_string($m[10]).'"
)
';
		$db->query($query);
		//time_nanosleep(0, 50000);
		//if($i>=10) {
		//	tpt_dump($buffer, true);
		//}
		//$i++;
	}

	if (!feof($handle)) {
		echo "Error: unexpected fgets() fail\n";
	}
	fclose($handle);
}
*/


/*
function dashNull($var) {
	if(preg_match('#[\D]#', $var)) {
		return null;
	}

	return $var;
}

$files = array(
	'log_20160906.txt',
	'log_20160907.txt',
	'log_20160908.txt',
	'log_20160909.txt',
	'log_20160910.txt',
	'log_20160911.txt',
	'log_20160912.txt',
	'log_20160913.txt',
	'log_20160914.txt',
	'log_20160915.txt',
	'log_20160916.txt',
	'log_20160916_1.txt',
	'log_20160916_2.txt',
	'log_20160917.txt',
	'log_20160918.txt',
	'log_20160919.txt',
	'log_20160920.txt',
	'log_20160921.txt',
	'log_20160922.txt',
	'log_20160923.txt',
	'log_20160924.txt',
	'log_20160925.txt',
	//'log_20160926.txt',
);


foreach($files as $file) {
	$handle = fopen('/var/log/apache2/user/'.$file, "r");
	if ($handle) {
		while (($line = fgets($handle)) !== false) {
			//var_dump($line);die();
			if(strstr($line, '/|\\')===false) {
				continue;
			}

			list($logType, $unique_id, $ipAddress, $logname, $user, $tDateTime, $request, $status, $size, $referrer, $user_agent, $cookie, $setcookie, $time_taken) = explode('/|\\', $line);

			list($d, $M, $y, $h, $m, $s, $z) = sscanf(substr($tDateTime, 0, 28), "[%2d/%3s/%4d:%2d:%2d:%2d %5s]");
			$serverTimestamp = strtotime("$d $M $y $h:$m:$s $z");
			$dateTime = date('Y-m-d H:i:s', $serverTimestamp);

			$time = $serverTimestamp;
			$size = dashNull($size);
			$time_taken = dashNull($time_taken);
			$request_hash = sha1($request);
			$referrer_hash = sha1($referrer);
			$user_agent_hash = sha1($user_agent);

			if(($logType != 1)) {
				continue;
			}

			$query = "
INSERT INTO
	`amazingw_logs_server`.`apache_access_log_https_copy`
SET
	unique_id=:unique_id,
	timestamp_server=:timestamp_server,
	date_time=:date_time,
	timezone=:timezone,
	timestamp=:timestamp,
	time_taken=:time_taken,
	ip_address=:ip_address,
	log_name=:log_name,
	user=:user,
	request=:request,
	status=:status,
	size=:size,
	referrer=:referrer,
	user_agent=:user_agent,
	cookie=:cookie,
	setcookie=:setcookie,
	request_hash=:request_hash,
	referrer_hash=:referrer_hash,
	user_agent_hash=:user_agent_hash,
	complete_data=:complete_data
";

			$db->prepare($query);
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
			$db->execute();
		}

		fclose($handle);
	}

}
*/

/*
$lines = file('/var/log/apache2/user/'.$file);
foreach($lines as $line) {
	//var_dump($line);die();
	if(strstr($line, '/|\\')===false) {
		continue;
	}

	list($logType, $unique_id, $ipAddress, $logname, $user, $tDateTime, $request, $status, $size, $referrer, $user_agent, $cookie, $setcookie, $time_taken) = explode('/|\\', $line);

	list($d, $M, $y, $h, $m, $s, $z) = sscanf(substr($tDateTime, 0, 28), "[%2d/%3s/%4d:%2d:%2d:%2d %5s]");
	$serverTimestamp = strtotime("$d $M $y $h:$m:$s $z");
	$dateTime = date('Y-m-d H:i:s', $serverTimestamp);

	$time = $serverTimestamp;
	$size = dashNull($size);
	$time_taken = dashNull($time_taken);
	$request_hash = sha1($request);
	$referrer_hash = sha1($referrer);
	$user_agent_hash = sha1($user_agent);

	if(($logType != 1)) {
		continue;
	}

	$query = "
INSERT INTO
`amazingw_logs_server`.`apache_access_log_https_copy`
SET
unique_id=:unique_id,
timestamp_server=:timestamp_server,
date_time=:date_time,
timezone=:timezone,
timestamp=:timestamp,
time_taken=:time_taken,
ip_address=:ip_address,
log_name=:log_name,
user=:user,
request=:request,
status=:status,
size=:size,
referrer=:referrer,
user_agent=:user_agent,
cookie=:cookie,
setcookie=:setcookie,
request_hash=:request_hash,
referrer_hash=:referrer_hash,
user_agent_hash=:user_agent_hash,
complete_data=:complete_data
";

	$db->prepare($query);
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
	$db->execute();
}
*/