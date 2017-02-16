<?php
defined('TPT_INIT') or die('Access Denied');

class tpt_Database {
	public $mysqli;
	public $PDO;
	public $PDOstmt;
	public $PDOexception;
	public $RESconnection;
	public $RESquery;
	public $RESerrors;
	public $query;
	public $engine;
	public $engines=array(
		'0'=>'legacy',
		'1'=>'PDO',
		'2'=>'mysql'
	);

	public $strict;

	public $types = array(
		'int'=>array('tinyint', 'smallint', 'int', 'mediumint', 'longint'),
		'float'=>array('float', 'double'),
		'string'=>array('char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'tinyblob', 'blob', 'mediumblob', 'longblob'),
		'date'=>array('date'),
		'datetime'=>array('datetime')
	);

	function __destruct() {
		//switch($this->engine) {
		//	case 1:
		unset($this->PDO);
		//		break;
		//	case 2:
		//		$this->mysqli = mysqli_close($this->mysqli);
		//		unset($this->mysqli);
		//		break;
		//	default:
		mysql_close($this->RESconnection);
		//		unset($this->RESconnection);
		//		break;
		//}
	}
	function __construct($host, $username, $password, $database, $strict=false) {

		$this->strict = $strict;

		//$this->engine = intval($engine, 10);
		//switch($this->engine) {
		//	case 1:
		try {
			$this->PDO = new PDO("mysql:host=" . $host . ";dbname=" . $database.';charset=utf8', $username, $password);
			$this->PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}catch(PDOException $exception) {
			tpt_dump("Connection error: " . $exception->getMessage());
			$this->PDOexception = $exception;
		}
		//	break;
		//	case 2:
		//		$this->mysqli = @mysqli_connect($host, $username, $password, $database);
		//	break;
		//	default:
		$this->RESconnection = @mysql_connect( $host, $username, $password);
		mysql_set_charset('utf8');

		if( $this->RESconnection === false ) {
			echo 'Error connecting to database.<br />'."\n";
			echo mysql_error();
			die();
		}

		mysql_select_db($database, $this->RESconnection);
		//		break;
		//}
	}

	/*
		function &getInstance($host='', $username='', $password='', $database='', $engine=0, $strict=false) {
			static $instance;

			//if(!isset($instance)) {
				return new tpt_Database($host, $username, $password, $database, $engine, $strict);
			//	$instance = new tpt_Database($host, $username, $password, $database, $engine, $strict);
			//	return $instance;
			//} else {
			//	return $instance;
			//}
		}
	*/
	function close(){
		//switch($this->engine) {
		//	case 1:
		unset($this->PDO);
		//		break;
		//	case 2:
		//		$this->mysqli = mysqli_close($this->mysqli);
		//		unset($this->mysqli);
		//		break;
		//	default:
		mysql_close($this->RESconnection);
		unset($this->RESconnection);
		//		break;
		//}
	}





	// Added from Renold's code
	function mysqli_escape($text, $extra = false){
		$result = mysqli_real_escape_string($this->mysqli, $text);

		if ($extra) $result = addcslashes($result, '%_');

		return $result;
	}

	function mysqli_prepare($text, $escape = true, $null = false,  $extra = false){
		if($text === 'NULL') { return 'NULL'; }

		$text = trim($text);

		if($null && $text == '') return 'NULL';

		if($escape) { return "'" . escape($text, $extra, $this->mysqli) . "'"; }
		else { return "'" . $text . "'"; }
	}

	function mysqli_query($query) {
		$this->query = $query;
		$this->RESquery = mysqli_query($this->mysqli, $query);
	}

	function mysqli_fetch() {
		return mysqli_fetch_array($this->RESquery, MYSQLI_ASSOC);
	}

	function mysqli_getRowCount(){
		return $this->RESquery->num_rows;
	}

	function mysqli_getResult($query){
		$this->mysqli_query($query);
		return $this->mysqli_fetch();
	}

	function mysqli_getResults($query){
		$this->mysqli_query($query);

		while($row = $this->mysqli_fetch()) $result[] = $row;

		if(count($result) == 1)	return $result[0];

		return $result;
	}

	/* = general sql query builder
	------------------------------------------------------------------------*/
	function mysqli_getField($field){
		return $field = $field == '' ? '*' : $field;
	}

	function mysqli_getList(&$input){
		$sql = "SELECT " . $this->mysqli_getField($input['field']) . " FROM " . $input['table'];

		if($input['where'] != '') { $sql .= " WHERE " . $input['where']; }

		if(@$input['orderby_key'] != '' && @$input['orderby_value'] != ''){
			$sql .= " ORDER BY " . $input['orderby_key'] . " " . $input['orderby_value'];
		}

		if($input['paged'] === 1) return page($sql, $input['max_per_page'], $input['row_count']);
		else $this->mysqli_query($sql);
	}

	function mysqli_getInfo($input, $dbObj = ''){
		$sql = "SELECT " . $this->mysqli_getField($input['field']) . " FROM " . $input['table'] . " WHERE " . $input['key'] . " = " . prepare($input['value']);
		return $this->mysqli_getResult($sql);
	}

	function mysqli_deleteInfo($input){
		$sql = "DELETE FROM " . $input['table'] . " WHERE " . $input['key'] . " = " . $input['value'];
		$this->mysqli_query($sql);
	}

	function mysqli_setInfo($input){
		global $public;

		if($input['act'] == "add") { $sql = "INSERT INTO "; }
		else if($input['act'] == "edit") { $sql = "UPDATE "; }

		$sql .= $input['table'] . " SET " . $this->mysqli_buildSetInput($input);

		if($input['act'] == "edit"){
			$sql .= " WHERE " . $input['key'] . " = " . $this->mysqli_prepare($input['value']);
		}

		$this->mysqli_query($sql);
	}
	function mysqli_last_id() {
		return mysqli_insert_id($this->mysqli);
	}

	function mysqli_buildSetInput($data){
		foreach($data['input'] as $key => $value){
			$result[] = $key ."=" . $this->mysqli_prepare($value);
		}
		return implode(",", $result);
	}

	// PAGINATION FUNCTIONS
	function page_getSN($page){
		$page = $page == '' ? 1 : $page;
		$page = $page == 1 ? 0 : ($page-1);
		$no = $page * 30;
		return $no;
	}

	function page_page($sql, &$maxPerPage = 'all', &$rowCount = '', $pageNo = '', $dbCon = ''){
		$maxPerPage = $maxPerPage == '' ? "all" : $maxPerPage;
		if($maxPerPage != "all" ) {
			$result['pagination'] = $this->page_pageIt($sql, $maxPerPage, $rowCount, $pageNo, $dbCon);
			$result['query'] = $this->mysqli_query($result['pagination']['sql']);
		} else {
			$result = $this->mysqli_query($sql);
		}
		return $result;
	}

	function page_getCurrentPage($pageNo = ''){
		if($pageNo != '') return $pageNo;

		if(isset($_GET['page'])) return $_GET['page'];

		// set 1 as current page
		$_GET['page'] = 1;
		return $_GET['page'];
	}

	function page_getMaxResultPerPage($maxPerPage){
		return $maxPerPage;
	}

	function page_getPageBaseUrl(){
		return BASE_URL;
	}

	function page_getNbResults($sql, $dbCon){
		if(!preg_match('/.*(GROUP BY|UNION ALL).*/i', $sql)) {
			$countSql = preg_replace('/(select)(.*)(from.*)/i', '$1 COUNT(*) as rowCount $3', $sql);
			$this->mysqli_query($countSql);
			$result = $this->mysqli_fetch();
			return $result['rowCount'];
		} else {
			$this->mysqli_query($sql);
			return $this->RESquery->num_rows;
		}
	}

	function page_getNbPages($noResult, $maxPerPage){
		$nbPages = ceil($noResult/$maxPerPage);
		return $nbPages;
	}

	function page_getPrintablePage(){
		return 5;
	}

	function page_paginationUrl($pageNo, $curPageNo, $baseUrl){
		if($pageNo == $curPageNo) return "javascript::";
		if($pageNo != '') return $baseUrl."&page=".$pageNo;

		return "javascript::";
	}

	function page_pageIt($sql, &$maxPerPage, &$rowCount = '', $pageNo = '', $dbCon = '') {
		$pageNo = $this->page_getCurrentPage($pageNo);
		$maxPerPage = $this->page_getMaxResultPerPage($maxPerPage);
		$baseUrl = $this->page_getPageBaseUrl();
		$printablePage = $this->page_getPrintablePage();

		// Set total row count
		if($rowCount == '') {
			$rowCount = $this->page_getNbResults($sql, $dbCon);
			$page['rows'] = $rowCount;
		}

		// Get starting result count of page
		$page['from'] = ($pageNo-1)*$maxPerPage;

		// Limit sql
		$page['sql'] = $sql." LIMIT ".$page['from'].",".$maxPerPage;

		$nbPages = $this->page_getNbPages($rowCount, $maxPerPage);

		if($nbPages > 1){
			$page['page']['current'] = $pageNo;
			$page['page']['end'] = $nbPages;

			// Find pre & next page
			$prePage = $pageNo-1;
			if($prePage >= 1) $page['page']['pre'] = $prePage;

			$nxtPage = $pageNo+1;
			if($nxtPage <= $nbPages) $page['page']['nxt'] = $nxtPage;

			// Find starting page text
			$pageTextStart = $pageNo-$printablePage;
			if($pageTextStart < 1 ) $pageTextStart = 1;

			// Find ending page text
			$pageTextEnd = $pageNo+$printablePage;
			if($pageTextEnd > $nbPages) $pageTextEnd = $nbPages;

			// Pagination
			for($i = $pageTextStart; $i <= $pageTextEnd; $i++){
				$page['page']['no'][] = $i;
			}

		}

		return $page;

	}
	//END
	// END




	function errorCodeConnection() {
		if(!empty($this->PDO)) {
			return $this->PDO->errorCode();
		}

		return false;
	}
	function errorInfoConnection() {
		if(!empty($this->PDO)) {
			return $this->PDO->errorInfo();
		}

		return false;
	}
	function errorCode() {
		if(!empty($this->PDOstmt)) {
			return $this->PDOstmt->errorCode();
		}

		return false;
	}
	function errorInfo() {
		if(!empty($this->PDOstmt)) {
			return $this->PDOstmt->errorInfo();
		} else {
			return false;
		}
	}
	function bindParam($param, $value) {
		if(!empty($this->PDOstmt)) {
			return $this->PDOstmt->bindParam($param, $value);
		} else {
			return false;
		}
	}
	function lastInsertId() {
		if(!empty($this->PDOstmt)) {
			$this->PDO->lastInsertId();
		}
	}
	function fetchAll($fetch_style=null, $fetch_argument=null, $ctor_args = array()) {
		if(!empty($this->PDOstmt)) {
			if(!empty($fetch_style)) {
				return $this->PDOstmt->fetchAll($fetch_style, $fetch_argument, $ctor_args);
			} else {
				return $this->PDOstmt->fetchAll($fetch_style);
			}
		} else {
			return false;
		}
	}
	function fetch($fetch_style=PDO::FETCH_ASSOC, $cursor_orientation = PDO::FETCH_ORI_NEXT, $cursor_offset = 0 ) {
		if(!empty($this->PDOstmt)) {
			return $this->PDOstmt->fetch($fetch_style, $cursor_orientation, $cursor_offset);
		} else {
			return false;
		}
	}
	function fetchAllIndexed($keyInd = '', $splitDuplicateKeys = true) {
		if(!empty($this->PDOstmt)) {
			$resRow = array();
			$resArray = array();
			$sKeyInd = false;
			if(is_array($keyInd)) {
				$sKeyInd = end($keyInd);
				$keyInd = reset($keyInd);
			}
			while($resRow = $this->PDOstmt->fetch(PDO::FETCH_ASSOC)) {
				if (!empty($keyInd)) {

					if ($splitDuplicateKeys) {
						if (isset($resArray[$resRow[$keyInd]]) && is_array($resArray[$resRow[$keyInd]])) {
							if (!empty($sKeyInd)) {
								$resArray[$resRow[$keyInd]][$resRow[$sKeyInd]] = $resRow;
							} else {
								$resArray[$resRow[$keyInd]][] = $resRow;
							}
						} else {
							$resArray[$resRow[$keyInd]] = array();
							if (!empty($sKeyInd)) {
								$resArray[$resRow[$keyInd]][$resRow[$sKeyInd]] = $resRow;
							} else {
								$resArray[$resRow[$keyInd]][] = $resRow;
							}
						}
					} else {
						$resArray[$resRow[$keyInd]] = $resRow;
					}
				} else {
					$resArray[] = $resRow;
				}
			}

			return $resArray;
		} else {
			return false;
		}
	}
	function dumpError() {
		if($this->strict) {
			echo 'Error occured during query execution for the following query:.<br />'."\n";
			echo $this->query.'<br />'."\n";
			echo 'The server returned:.<br />'."\n";
			echo $this->errorInfo();
		}
		global $tpt_vars;
		$unlog = false;
		$bt_bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		array_shift($bt_bck);
		end($bt_bck);
		$skey = prev($bt_bck);

		$bt_cls0 = !empty($bt_bck[0]['class'])?$bt_bck[0]['class']:'';
		$bt_fn0 = !empty($bt_bck[0]['function'])?$bt_bck[0]['function']:'';
		$bt_file0 = !empty($bt_bck[0]['file'])?$bt_bck[0]['file']:'';
		$bt_line0 = !empty($bt_bck[0]['line'])?$bt_bck[0]['line']:'';
		$bt_cls1 = !empty($bt_bck[1]['class'])?$bt_bck[1]['class']:'';
		$bt_fn1 = !empty($bt_bck[1]['function'])?$bt_bck[1]['function']:'';
		$bt_file1 = !empty($bt_bck[1]['file'])?$bt_bck[1]['file']:'';
		$bt_line1 = !empty($bt_bck[1]['line'])?$bt_bck[1]['line']:'';
		$bt_cls_n = !empty($skey['class'])?$skey['class']:'';
		$bt_fn_n = !empty($skey['function'])?$skey['function']:'';
		$bt_file_n = !empty($skey['file'])?$skey['file']:'';
		$bt_line_n = !empty($skey['line'])?$skey['line']:'';
		/*
		if(($bt_cls0 == 'tpt_Database') && ($bt_fn0 == 'query')) {
			array_shift($bt_bck);
			$bt_cls = !empty($bt_bck[0]['class'])?$bt_bck[0]['class']:'';
			$bt_fn = !empty($bt_bck[0]['function'])?$bt_bck[0]['function']:'';
			$bt_file = !empty($bt_bck[0]['file'])?$bt_bck[0]['file']:'';
			$bt_line = !empty($bt_bck[0]['line'])?$bt_bck[0]['line']:'';

		}
		*/
		if(($bt_cls0 == 'tpt_logger') && ($bt_fn0 == 'log_query_error')) {
			$unlog = true;
		}
		//tpt_dump($unlog);
		$file = '';
		$line = '';
		if($tpt_vars['config']['dev']['query_autostore_backtrace']) {
			$file = $bt_file_n;
			$line = $bt_line_n;
		}

		$error = $this->RESerrors;
		if(empty($unlog) && !empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_query_errors'])) {
			tpt_logger::log_query_error($tpt_vars, 'tpt_request_rq_query_errors', $this->query, $file, $line, $this->errorInfo());
		}
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev']));
		//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log']));
		//tpt_dump(isDevLog());
		//tpt_dump($unlog, true);

		if(empty($unlog) && isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev'])) {

			//tpt_dump($query, true);
			tpt_logger::log_query_error($tpt_vars, 'tpt_request_rq_query_errors_dev', $this->query, $file, $line, $this->errorInfo());
		}

		// LEAVE THE FOLLOWING 3 DUMPS
		tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
		tpt_dump($this->errorInfo());
		tpt_dump($this->query);
		// !!!
		if(isDump() && !empty($tpt_vars['config']['dev']['break_on_query_error'])) {
			die();
		}
	}
	function execute() {
		if(!empty($this->PDOstmt)) {
			$result = $this->PDOstmt->execute();
			if(!$result) {
				$this->dumpError();
			}
			return $result;
		} else {
			return false;
		}
	}
	function prepare($query) {
		$this->query = $query;
		$this->PDOstmt = $this->PDO->prepare($query);

		return $this->PDOstmt;
	}
	function quote($string) {
		return $this->PDO->quote($string);
	}
	function use_db($database) {
		$this->query = 'USE `'.$this->quote($database).'`';
		$this->PDOstmt = $this->PDO->prepare($this->query);
		return $this->PDOstmt->execute();
	}
	function table_exists($table, $database='') {
		$this->query = 'SELECT 1 FROM `'.$table.'` LIMIT 1';
		if(!empty($database)) {
			$this->query = 'SELECT 1 FROM `'.$database.'`.`'.$table.'` LIMIT 1';
		}
		$this->PDOstmt = $this->PDO->prepare($this->query);
		return $this->PDOstmt->execute();
	}
	function query($query, $file='', $line='') {
		//$this->RESquery = mysql_query(mysql_real_escape_string(preg_replace('/\R/', ' ', $query)), $this->RESconnection);
		$this->query = $query;

		//preg_match('#(SELECT|UPDATE|INSERT|DELETE|SHOW COLUMNS)[\s]+#i', $query);

		//tpt_dump($query);
		$this->RESquery = mysql_query($query, $this->RESconnection);
		//tpt_dump(mysql_error($this->RESconnection), true);

		if($this->RESerrors = mysql_error($this->RESconnection)) {


			if($this->strict) {
				echo 'Error occured during query execution for the following query:.<br />'."\n";
				echo $query.'<br />'."\n";
				echo 'The server returned:.<br />'."\n";
				echo $this->RESerrors;
			}
			global $tpt_vars;
			$unlog = false;
			$bt_bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
			array_shift($bt_bck);
			end($bt_bck);
			$skey = prev($bt_bck);

			$bt_cls0 = !empty($bt_bck[0]['class'])?$bt_bck[0]['class']:'';
			$bt_fn0 = !empty($bt_bck[0]['function'])?$bt_bck[0]['function']:'';
			$bt_file0 = !empty($bt_bck[0]['file'])?$bt_bck[0]['file']:'';
			$bt_line0 = !empty($bt_bck[0]['line'])?$bt_bck[0]['line']:'';
			$bt_cls1 = !empty($bt_bck[1]['class'])?$bt_bck[1]['class']:'';
			$bt_fn1 = !empty($bt_bck[1]['function'])?$bt_bck[1]['function']:'';
			$bt_file1 = !empty($bt_bck[1]['file'])?$bt_bck[1]['file']:'';
			$bt_line1 = !empty($bt_bck[1]['line'])?$bt_bck[1]['line']:'';
			$bt_cls_n = !empty($skey['class'])?$skey['class']:'';
			$bt_fn_n = !empty($skey['function'])?$skey['function']:'';
			$bt_file_n = !empty($skey['file'])?$skey['file']:'';
			$bt_line_n = !empty($skey['line'])?$skey['line']:'';
			/*
			if(($bt_cls0 == 'tpt_Database') && ($bt_fn0 == 'query')) {
				array_shift($bt_bck);
				$bt_cls = !empty($bt_bck[0]['class'])?$bt_bck[0]['class']:'';
				$bt_fn = !empty($bt_bck[0]['function'])?$bt_bck[0]['function']:'';
				$bt_file = !empty($bt_bck[0]['file'])?$bt_bck[0]['file']:'';
				$bt_line = !empty($bt_bck[0]['line'])?$bt_bck[0]['line']:'';

			}
			*/
			if(($bt_cls0 == 'tpt_logger') && ($bt_fn0 == 'log_query_error')) {
				$unlog = true;
			}
			//tpt_dump($unlog);
			if($tpt_vars['config']['dev']['query_autostore_backtrace']) {
				$file = $bt_file_n;
				$line = $bt_line_n;
			}

			$error = $this->RESerrors;
			if(empty($unlog) && !empty($tpt_vars['config']['logger']['db_rq_log']) && !empty($tpt_vars['config']['logger']['db_rq_log_query_errors'])) {
				tpt_logger::log_query_error($tpt_vars, 'tpt_request_rq_query_errors', $query, $file, $line, $error);
			}
			//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev']));
			//tpt_dump(!empty($tpt_vars['config']['dev']['logger']['db_rq_log']));
			//tpt_dump(isDevLog());
			//tpt_dump($unlog, true);

			if(empty($unlog) && isDevLog() && !empty($tpt_vars['config']['dev']['logger']['db_rq_log']) && !empty($tpt_vars['config']['dev']['logger']['db_rq_log_query_errors_dev'])) {

				//tpt_dump($query, true);
				tpt_logger::log_query_error($tpt_vars, 'tpt_request_rq_query_errors_dev', $query, $file, $line, $error);
			}

			// LEAVE THE FOLLOWING 3 DUMPS
			tpt_dump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
			tpt_dump($error);
			tpt_dump($query);
			// !!!
			if(isDump() && !empty($tpt_vars['config']['dev']['break_on_query_error'])) {
				die();
			}
		} else {
			$this->RESerrors = '';
		}
		//var_dump($this->query);//die();
		//var_dump($this->RESquery);//die();
		//var_dump($this->RESerrors);//die();
		return $this->RESquery;
	}

	function show_columns($table, $database='') {
		if (empty($table)) {
			return false;
		}
		//var_dump($this->query('select 1 from `'.$table.'`'));die();
		// Renold - added LIMIT 1 for high performance
		$query = 'SELECT 1 FROM `'.$table.'` LIMIT 1';
		if(!empty($database)) {
			$query = 'SELECT 1 FROM `'.$database.'`.`'.$table.'` LIMIT 1';
		}
		//var_dump($this->query($query));die();
		$result = false;
		if($this->query($query)) {
			$result = array();

			$query = 'SHOW COLUMNS FROM `'.$table.'`';
			if(!empty($database)) {
				$query = 'SHOW COLUMNS FROM `'.$database.'`.`'.$table.'`';
			}
			$this->query($query);
			while($field = $this->fetch_assoc()) {
				$result[$field['Field']] = $field;
			}
		} else {
		}
		//tpt_dump($result, true);
		return $result;
	}

	function get_tables($tableName = '', $database = '') {
		$queryString = 'SHOW TABLES';
		if ($database != '') {
			$queryString .= ' FROM `'.$database.'`';
		}
		if ($tableName != '') {
			$queryString .= ' LIKE "'.$tableName.'"';
		}
		$this->query($queryString);
		$tables = array();
		if (mysql_num_rows($this->RESquery)) {
			while ($table = mysql_fetch_array($this->RESquery)) {
				$tables[$table[0]] = $table[0];
			}
		}
		return $tables;
	}

	function fetch_array() {
		if(is_resource($this->RESquery)) {
			if(mysql_num_rows($this->RESquery)) {
				$array = mysql_fetch_array( $this->RESquery );
				if(!empty($array)) {
					return $array;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function fetch_assoc() {
		if(is_resource($this->RESquery)) {
			if(mysql_num_rows($this->RESquery)) {
				$assoc = mysql_fetch_assoc( $this->RESquery );
				if(!empty($assoc)) {
					return $assoc;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	function fetch_assoc_list($keyInd = '', $splitDuplicateKeys = true) {

		$resRow = array();
		$resArray = array();
		$sKeyInd = false;
		if(is_array($keyInd)) {
			$sKeyInd = end($keyInd);
			$keyInd = reset($keyInd);
		}



		if(is_resource($this->RESquery)) {
			if((mysql_num_rows($this->RESquery) > 0)) {

				//if(strstr($this->query, '`temp_custom_orders` AS `asd`') !== false) {
				//	tpt_dump(mysql_num_rows($this->RESquery), true);
				//}
				while ($resRow = mysql_fetch_assoc($this->RESquery)) {
					if (!empty($keyInd)) {

						if ($splitDuplicateKeys) {
							if (isset($resArray[$resRow[$keyInd]]) && is_array($resArray[$resRow[$keyInd]])) {
								if (!empty($sKeyInd)) {
									$resArray[$resRow[$keyInd]][$resRow[$sKeyInd]] = $resRow;
								} else {
									$resArray[$resRow[$keyInd]][] = $resRow;
								}
							} else {
								$resArray[$resRow[$keyInd]] = array();
								if (!empty($sKeyInd)) {
									$resArray[$resRow[$keyInd]][$resRow[$sKeyInd]] = $resRow;
								} else {
									$resArray[$resRow[$keyInd]][] = $resRow;
								}
							}
						} else {
							$resArray[$resRow[$keyInd]] = $resRow;
						}
					} else {
						$resArray[] = $resRow;
					}
				}
				//if(strstr($this->query, '`temp_custom_orders` AS `asd`') !== false) {
				//	tpt_dump($this->query, true);
				//}


				mysql_data_seek($this->RESquery, 0);


				//tpt_dump($resArray, true);
				return $resArray;
			} else {
				return array();
			}
		} else {
			return false;
		}
	}

	function getTableFieldFromId($table, $field, $id) {
		$this->query('SELECT `'.$field.'` FROM `'.$table.'` WHERE id='.$id);
		$res = $this->fetch_assoc();

		if(is_array($res) && !empty($res[$field]))
			return $res[$field];
		else
			return false;
	}

	function getTableFieldFromField($table, $field, $search_field, $value) {
		$this->query('SELECT `'.$field.'` FROM `'.$table.'` WHERE `'.$search_field.'`="'.$value.'"');
		$res = $this->fetch_assoc();

		if(is_array($res) && !empty($res[$field]))
			return $res[$field];
		else
			return false;
	}

	function num_rows() {
		if(is_resource($this->RESquery)) {
			if((strpos(strtolower($this->query), 'select') === 0) || (strpos(strtolower($this->query), 'show') === 0)) {
				return mysql_num_rows($this->RESquery);
			} else if((strpos(strtolower($this->query), 'insert') === 0) || (strpos(strtolower($this->query), 'update') === 0) || (strpos(strtolower($this->query), 'replace') == 0) || (strpos(strtolower($this->query), 'delete') == 0)) {
				return mysql_affected_rows($this->RESquery);
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	}

	function last_id() {
		//tpt_dump($this->RESconnection);
		//tpt_dump($this->query, true);
		if(is_resource($this->RESconnection)) {
			if(strpos(strtolower(trim($this->query)), 'insert') === 0)
				return mysql_insert_id($this->RESconnection);
			else
				return false;
		} else {
			return false;
		}
	}

	function errors($format=false) {
		if($format) {
			$out = '';
			$out .= 'Error occured during query execution for the following query:.<br />'."\n";
			$out .= $this->query.'<br />'."\n";
			$out .= 'The server returned:.<br />'."\n";
			$out .= $this->RESerrors;
			return $out;
		} else {
			return $this->RESerrors;
		}
	}

	function insertData(&$vars, $table, $columns, $values) {
		//$keys = array_keys($columns);
		//$columns = array_values($columns);
		//$qvalues = array_values($values);


		$pcolumns = array();
		$pvalues = array();

		/*
		for($i=0, $_len=count($keys);$i<$_len; $i++) {
			if(isset()) {

			}
		}
		*/
		//tpt_dump($columns, true);
		foreach($values as $key=>$value) {
			if(!empty($columns[$key]) && array_key_exists($key, $values)) {
				$val = $this->validate($vars, $value, $columns[$key]);
				if($val !== false) {
					$pcolumns[] = $key;
					//tpt_dump($columns[$key], true);
					$pvalues[] = $val;
				}
			}
		}

		//tpt_dump($values, true);
		//tpt_dump($pcolumns, true);

		//tpt_dump($pvalues, true);
		$pcolumns = "\t\t\t".'`'.implode('`,'."\n\t\t\t".'`', $pcolumns).'`';
		$pvalues = "\t\t\t".implode(','."\n\t\t\t".'', $pvalues);
		$query = <<< EOT
		INSERT INTO
			`$table`
		(
$pcolumns
		)
		VALUES(
$pvalues
		)
EOT;
		//tpt_dump($query, true);
		if(isDump() && !empty($vars['config']['dev']['debuginsertorder_outputqueries'])) {
			tpt_dump($query);
		}
		if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipqueries'])) {
			$vars['db']['handler']->query($query);
		}
		$last_id = $this->last_id();

		if(($table == ORDERS_TABLE) && !empty($vars['config']['logger']['db_rq_log']) && !empty($vars['config']['logger']['db_rq_log_common'])) {
			tpt_logger::log_common($vars, 'tpt_request_rq_iorder_query', 'INSERT ORDER', $query);
		}
		if(($table == ORDERS_TABLE) && isDevLog() && !empty($vars['config']['dev']['logger']['db_rq_log']) && !empty($vars['config']['dev']['logger']['db_rq_log_common_dev'])) {
			tpt_logger::log_common($vars, 'tpt_request_rq_iorder_query_dev', 'INSERT ORDER', $query);
		}

		return $last_id;
	}

	function validate(&$vars, $value, $column, $context='plain') {
		if(!empty($column)) {
			if($context == 'like3') {
				if(is_null($value)) {
					return 'NULL';
				} else if (preg_match('#(blob)|(char)|(text)|(date)#', $column['Type'])) {
					return '"%' . mysql_real_escape_string($value) . '%"';
				} else if (preg_match('#(float)|(double)#', $column['Type'])) {
					return '"%' . floatval($value) . '%"';
				} else {
					return '"%' . intval($value, 10) . '%"';
				}
			} else  {
				//tpt_dump($columns[$key], true);
				if(is_null($value)) {
					return 'NULL';
				} else if (preg_match('#(blob)|(char)|(text)|(date)#', $column['Type'])) {
					return '"' . mysql_real_escape_string($value) . '"';
				} else if (preg_match('#(float)|(double)#', $column['Type'])) {
					return floatval($value);
				} else {
					return intval($value, 10);
				}
			}
		}

		return false;
	}

	function updateData(&$vars, $table, $columns, $values, $where) {
		//$keys = array_keys($columns);
		//$columns = array_values($columns);
		//$qvalues = array_values($values);


		$pcolumns = array();
		$pvalues = array();

		/*
		for($i=0, $_len=count($keys);$i<$_len; $i++) {
			if(isset()) {

			}
		}
		*/
		//tpt_dump($values, true);
		//tpt_dump($columns, true);
		foreach($values as $key=>$value) {
			//tpt_dump($values[$key]);
			//tpt_dump(isset($values[$key]));
			if(!empty($columns[$key]) && array_key_exists($key, $values)) {
				$pcolumns[] = $key;
				//tpt_dump($columns[$key], true);
				if(is_null($value)) {
					$pvalues[] = 'NULL';
				} else if(preg_match('#(blob)|(char)|(text)|(date)#', $columns[$key]['Type'])) {
					$pvalues[] = '"'.mysql_real_escape_string($value).'"';
				} else if(preg_match('#(float)|(double)#', $columns[$key]['Type'])) {
					$pvalues[] = floatval($value);
				} else {
					$pvalues[] = intval($value, 10);
				}
			}
		}

		//tpt_dump($pvalues, true);
		//tpt_dump($values, true);
		//tpt_dump($pcolumns, true);

		$whereKey = '';
		if(!empty($where)) {
			$where = ' WHERE '.$where;
			$whereKey = base64_encode($where);
		} else {
			$whereKey = 'default';
		}

		//tpt_dump($pvalues, true);
		//$pcolumns = "\t\t\t".'`'.implode('`,'."\n\t\t\t".'`', $pcolumns).'`';
		//$pvalues = "\t\t\t".implode(','."\n\t\t\t".'', $pvalues);
		$data = array_map(
			function($a, $b) {
				return '`'.$a.'`='.$b;
			},
			$pcolumns,
			$pvalues
		);
		$data = implode(', ', $data);

		$query = <<< EOT
		UPDATE
			`$table`
		SET
			$data
		$where
EOT;
		//tpt_dump($query, true);
		if(isDump() && !empty($vars['config']['dev']['debuginsertorder_outputqueries'])) {
			tpt_dump($query);
		}
		if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipqueries'])) {
			$vars['db']['handler']->query($query);
		}

		return true;
	}

	function selectData(&$vars, $table, $fields, $columns, $values, $where='', $indexBy=null, $splitKeys=null) {
		//$keys = array_keys($columns);
		//$columns = array_values($columns);
		//$qvalues = array_values($values);


		$pcolumns = array();
		$pvalues = array();

		/*
		for($i=0, $_len=count($keys);$i<$_len; $i++) {
			if(isset()) {

			}
		}
		*/
		//tpt_dump($values, true);
		//tpt_dump($columns, true);
		foreach($values as $key=>$value) {
			//tpt_dump($values[$key]);
			//tpt_dump(isset($values[$key]));
			if((!empty($columns[$key]) && array_key_exists($key, $values))) {
				$pcolumns[] = $key;
				//tpt_dump($columns[$key], true);
				if(is_null($value)) {
					$pvalues[] = '=NULL';
				} else if(preg_match('#(blob)|(char)|(text)|(date)#', $columns[$key]['Type'])) {
					if(empty($value)) {
						$value = '.*';
					}
					$pvalues[] = ' REGEXP "'.mysql_real_escape_string($value).'"';
				} else if(preg_match('#(float)|(double)#', $columns[$key]['Type'])) {
					$pvalues[] = '='.floatval($value);
				} else {
					$pvalues[] = '='.intval($value, 10);
				}
			} else if(preg_match('#([a-zA-Z]+?)(~\\{.*?\\}~)#', $key, $m)) {
				$pcolumns[] = $m[2];
				//tpt_dump($columns[$key], true);
				if(is_null($value)) {
					$pvalues[] = '=NULL';
				} else if(preg_match('#(blob)|(char)|(text)|(date)#', $m[1])) {
					if(empty($value)) {
						$value = '.*';
					}

					if((strpos($value, '~{') === 0) && (strpos($value, '}~') !== false)) {
						$pvalues[] = ' REGEXP '.str_replace('}~', '', str_replace('~{', '', $value));
					} else {
						$pvalues[] = ' REGEXP "'.mysql_real_escape_string($value).'"';
					}
				} else if(preg_match('#(float)|(double)#', $m[1])) {
					$pvalues[] = '='.floatval($value);
				} else {
					$pvalues[] = '='.intval($value, 10);
				}
			}
		}

		//tpt_dump($pvalues, true);
		//tpt_dump($values, true);
		//tpt_dump($pcolumns, true);

		/*
		$whereKey = '';
		if(!empty($where)) {
			$where = ' AND '.$where;
			$whereKey = base64_encode($where);
		} else {
			$whereKey = 'default';
		}
		*/

		//tpt_dump($pvalues, true);
		//$pcolumns = "\t\t\t".'`'.implode('`,'."\n\t\t\t".'`', $pcolumns).'`';
		//$pvalues = "\t\t\t".implode(','."\n\t\t\t".'', $pvalues);
		$data = array_map(
			function($a, $b) {
				if((strpos($a, '~{') === 0) && (strpos($a, '}~') !== false)) {
					return ''.str_replace('}~', '', str_replace('~{', '', $a)).''.$b;
				}
				return '`'.$a.'`'.$b;
			},
			$pcolumns,
			$pvalues
		);

		//tpt_dump($data);
		$data = implode(' AND ', $data);
		$wh = array($data, $where);
		$wh = array_filter($wh);
		if(!empty($wh)) {
			$wh = implode(' AND ', $wh);
			$where = <<< EOT
WHERE
	$wh
EOT;
		} else {
			$where = '';
		}

		$query = <<< EOT
		SELECT
			$fields
		FROM
			`$table`
		$where
EOT;
		//tpt_dump($query, true);
		if(isDump() && !empty($vars['config']['dev']['debuginsertorder_outputqueries'])) {
			tpt_dump($query);
		}
		if(!isDump() || empty($vars['config']['dev']['debuginsertorder_skipqueries'])) {
			$vars['db']['handler']->query($query);
		}

		return $vars['db']['handler']->fetch_assoc_list($indexBy, $splitKeys);
	}

	function getData(&$vars, $table, $fields='*', $where='', $indexBy=null, $splitKeys=null/*, $cache=false*/, $debug=false) {

		$table = preg_replace('#[^-a-zA-Z0-9_\.*]+#', ' ', $table);
		if(empty($table)) {
			return false;
		}
		$table = explode('.', $table);
		$ctable = count($table);
		if(empty($ctable)) {
			return false;
		} else if($ctable >= 2) {
			$table = $table[0].'`.`'.$table[1];
		} else {
			$table = implode($table);
		}

		$fields = $this->getFields($fields);
		$whereKey = '';
		if(!empty($where)) {
			$where = ' WHERE '.$where;
			$whereKey = base64_encode($where);
		} else {
			$whereKey = 'default';
		}
		$indexKey = (!empty($indexBy)?$indexBy:'default');
		$splitKey = (!empty($splitKeys)?'split':'default');

		if(empty($vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey]) || !is_array($vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey])) {
			$query = 'SELECT '.$fields['query'].' FROM `'.$table.'`'.$where;

			if($debug) {
				if(!empty($vars['config']['dev']['getData_backtrace'])) {
					$bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
					if(isDump()) {
						echo 'tpt_Database::getData(): ' . $bck[0]['file'] . ' (' . $bck[0]['line'] . '):' . '<br />';
					}
				}
				tpt_dump($query, true);
				//var_dump($query, true);
			}
			if($vars['config']['dev']['getData_autostore_backtrace']) {
				$bck = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
				$cls = !empty($bck[0]['class'])?$bck[0]['class']:'';
				$fn = !empty($bck[0]['function'])?$bck[0]['function']:'';
				$file = !empty($bck[0]['file'])?$bck[0]['file']:'';
				$line = !empty($bck[0]['line'])?$bck[0]['line']:'';
			}
			$this->query($query);
			$items = $vars['db']['handler']->fetch_assoc_list($indexBy, $splitKeys);
			//if(true || $cache) {
			$vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey] = $items;
			//}

		} else {
			$items = $vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey];
		}

		return $items;
	}

	function getColumn(&$vars, $table, $field, $where='', $indexBy=null, $splitKeys=null, $debug=false) {

		$table = preg_replace('#[^-a-zA-Z0-9_*]+#', ' ', $table);

		if(!is_array($field))
			$field = array($field);
		$field = array_pop($field);

		$fields = $this->getFields($field);
		$whereKey = '';
		if(!empty($where)) {
			$where = ' WHERE '.$where;
			$whereKey = base64_encode($where);
		} else {
			$whereKey = 'default';
		}
		$indexKey = (!empty($indexBy)?$indexBy:'default');
		$splitKey = (!empty($splitKeys)?'split':'default');

		if(empty($vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey]) || !is_array($vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey])) {
			$query = 'SELECT '.$fields['query'].' FROM `'.$table.'`'.$where;

			if($debug) {
				tpt_dump($query, true);
			}
			$this->query($query);
			$vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey] = $items = $vars['db']['handler']->fetch_assoc_list($indexBy, $splitKeys);
		} else {
			$items = $vars['data'][$table][$fields['id']][$whereKey][$indexKey][$splitKey];
		}


		//array_column($items, $field[]);
		return $items;
	}

	function getFields($fields) {

		$DONOT = 0;
		if(is_string($fields)) {
			if(strstr($fields, '*') !== false) {
				$fields = array($fields);
				$DONOT = 1;
			} else {
				$fields = str_replace('`', '', $fields);
				$fields = preg_split('#[^-a-zA-Z0-9_*]+#', $fields);
			}
		} else if(is_array($fields)) {
			$fields = implode(' ', $fields);
			$fields = preg_replace('#[^-a-zA-Z0-9_*]+#', ' ', $fields);
			$fields = explode(' ', $fields);
		}

		if($DONOT) {
			$fields = reset($fields);
		} else {
			$fields = implode('`,`', $fields);
		}

		if($DONOT) {
		} else {
			$fields = '`'.$fields.'`';
		}

		$fields = array('query'=>$fields, 'id'=>base64_encode($fields));

		return $fields;
	}

}
