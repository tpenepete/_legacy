<?php
defined('TPT_INIT') or die('access denied');

$db = $tpt_vars['db']['handler'];
$db2 = new tpt_Database($tpt_vars['config']['db']['host'], 'root', 'Fa7o6mRU5kEzM0u@6W', 'mysql');
$db_logs_database = DB_DB_DB_LOGS;

//$generalLogDeletePoint = "-1 months";
//$slowQueryLogDeletePoint = "-1 months";
$generalLogDeletePoint = "-1 year";
$slowQueryLogDeletePoint = "-1 day";
//disk clean-up
//general log
$prevDateGnrl = date('Y-m-d', strtotime($generalLogDeletePoint));
list($prevYearGnrl, $prevMonthGnrl, $prevDayGnrl) = explode('-', $prevDateGnrl);
$filesGnrl = glob("/var/log/mysql/archive/{$prevYearGnrl}/{$prevMonthGnrl}/{$prevDayGnrl}/general_{$prevDateGnrl}_*.log");
foreach ($filesGnrl as $filePath) {
	@unlink($filePath);
	$db2->prepare('INSERT INTO `amazingw_logs_db`.`cleanup_commands` (`date_time`, `timezone`, `data`) VALUES("'.date('Y-m-d H:i:s').'", "'.date('O').'", "'.$db2->quote('rm '.$filePath).'")');
	$db2->execute();
}

$tablesGnrl = $db2->get_tables("mysql_general_log_{$prevDateGnrl}%", DB_DB_DB_LOGS);
if (!empty($tablesGnrl) && is_array($tablesGnrl)) {
	$query = "DROP TABLE IF EXISTS `".implode('`, `', $tablesGnrl)."`";
	$db2->prepare($query);
	$db2->execute();
	$db2->prepare('INSERT INTO `amazingw_logs_db`.`cleanup_commands` (`date_time`, `timezone`, `data`) VALUES("'.date('Y-m-d H:i:s').'", "'.date('O').'", "'.$db2->quote($query).'")');
	$db2->execute();
}

//slow query log
$prevDateSqry = date('Y-m-d', strtotime($slowQueryLogDeletePoint));
list($prevYearSqry, $prevMonthSqry, $prevDaySqry) = explode('-', $prevDateSqry);
$filesSqry = glob("/var/log/mysql/archive/{$prevYearSqry}/{$prevMonthSqry}/{$prevDaySqry}/slowquery_{$prevDateSqry}_*.log");
foreach ($filesSqry as $filePath) {
	@unlink($filePath);
	$db2->prepare('INSERT INTO `amazingw_logs_db`.`cleanup_commands` (`date_time`, `timezone`, `data`) VALUES("'.date('Y-m-d H:i:s').'", "'.date('O').'", "'.$db2->quote('rm '.$filePath).'")');
	$db2->execute();
}

$tablesSqry = $db2->get_tables("mysql_slow_log_{$prevDateSqry}%", DB_DB_DB_LOGS);
if (!empty($tablesSqry) && is_array($tablesSqry)) {
	$query = "DROP TABLE IF EXISTS `".implode('`, `', $tablesSqry)."`";
	$db2->prepare($query);
	$db2->execute();
	$db2->prepare('INSERT INTO `amazingw_logs_db`.`cleanup_commands` (`date_time`, `timezone`, `data`) VALUES("'.date('Y-m-d H:i:s').'", "'.date('O').'", "'.$db2->quote($query).'")');
	$db2->execute();
}
//end

$zone = intval(date('O'), 10);
$date = date('Y-m-d_H-i-s').'_'.$zone;
$date2 = explode('-', date('Y-m-d'));
$year = $date2[0];
$month = $date2[1];
$day = $date2[2];


if(!file_exists('/var/log/mysql/archive/'.$year)) {
	mkdir('/var/log/mysql/archive/'.$year);
}
if(!file_exists('/var/log/mysql/archive/'.$year.'/'.$month)) {
	mkdir('/var/log/mysql/archive/'.$year.'/'.$month);
}
if(!file_exists('/var/log/mysql/archive/'.$year.'/'.$month.'/'.$day)) {
	mkdir('/var/log/mysql/archive/'.$year.'/'.$month.'/'.$day);
}


// GENERAL LOG
copy('/var/log/mysql/general.log', '/var/log/mysql/archive/'.$year.'/'.$month.'/'.$day.'/general_'.$date.'.log');
file_put_contents('/var/log/mysql/general.log', '');
$i=1;
$glog_table = 'mysql_general_log_'.$date;
while($db->table_exists($glog_table, $db_logs_database)) {
	if($i>=POTENTIAL_ENDLESS_LOOP_MAXIMUM_COUNTER) {
		if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_endless_loops'])) {
			tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops', $i."\n".$glog_table, __FILE__, __LINE__);
		}
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev']));
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log']));
		//tpt_dump(isDevLog());
		//tpt_dump($unlog, true);

		if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_endless_loops_dev'])) {

			//tpt_dump($query, true);
			tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops_dev', $i."\n".$glog_table, __FILE__, __LINE__);
		}

		break;
	}

	$i++;
	$glog_table = 'mysql_general_log_'.$date.'_'.$i;
}
//echo 'asd';
$query = <<< EOT
CREATE TABLE `$db_logs_database`.`$glog_table` LIKE `mysql`.`general_log`
EOT;
$db2->prepare($query);
$db2->execute();

$query = <<< EOT
ALTER TABLE `$db_logs_database`.`$glog_table` ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`), ADD INDEX `event_time` (`event_time`)
EOT;
$db2->prepare($query);
$db2->execute();

$query = <<< EOT
INSERT INTO
	`$db_logs_database`.`$glog_table`
SELECT NULL as `id`, `mysql`.`general_log`.* FROM `mysql`.`general_log`
EOT;
$db2->prepare($query);
$db2->execute();
//echo 'ddd';

$query = <<< EOT
TRUNCATE TABLE `mysql`.`general_log`
EOT;
$db2->prepare($query);
$db2->execute();






// SLOW LOG
copy('/var/log/mysql/slowquery.log', '/var/log/mysql/archive/'.$year.'/'.$month.'/'.$day.'/slowquery_'.$date.'.log');
file_put_contents('/var/log/mysql/slowquery.log', '');
$i=1;
$slog_table = 'mysql_slow_log_'.$date;
while($db->table_exists($slog_table, $db_logs_database)) {
	if($i>=POTENTIAL_ENDLESS_LOOP_MAXIMUM_COUNTER) {
		if(!empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_endless_loops'])) {
			tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops', $i."\n".$slog_table, __FILE__, __LINE__);
		}
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev']));
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log']));
		//tpt_dump(isDevLog());
		//tpt_dump($unlog, true);

		if(isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_endless_loops_dev'])) {

			//tpt_dump($query, true);
			tpt_logger::log_endless_loop($tpt_vars, 'tpt_request_rq_endless_loops_dev', $i."\n".$slog_table, __FILE__, __LINE__);
		}
	}

	$i++;
	$slog_table = 'mysql_slow_log_'.$date.'_'.$i;
}
$query = <<< EOT
CREATE TABLE `$db_logs_database`.`$slog_table` LIKE `mysql`.`slow_log`
EOT;
$db2->prepare($query);
$db2->execute();

$query = <<< EOT
ALTER TABLE `$db_logs_database`.`$slog_table` ADD COLUMN `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`), ADD INDEX `start_time` (`start_time`)
EOT;
$db2->prepare($query);
$db2->execute();

$query = <<< EOT
INSERT INTO
	`$db_logs_database`.`$slog_table`
SELECT NULL as `id`, `mysql`.`slow_log`.* FROM `mysql`.`slow_log`
EOT;
$db2->prepare($query);
$db2->execute();

$query = <<< EOT
TRUNCATE TABLE `mysql`.`slow_log`
EOT;
$db2->prepare($query);
$db2->execute();