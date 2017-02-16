<?php

defined('TPT_INIT') or die('access denied');

class tpt_Module {

	var $moduleClassFile;
	var $moduleClass;
	var $moduleTable;
	var $moduleName;

	var $fields;
	var $index;
	var $moduleData;
	var $queryStart;
	var $queryRowCount;
	var $pages;
	var $currentPage;
	var $records;

	public $load_unindexed = false;


	function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields = array(), $index = false) {
		//tpt_dump($name);
		//if($name == 'CustomProductField') {
		//	tpt_dump($fields, true);
		//}
		$this->moduleClassFile = $moduleClassFile;
		$this->moduleClass = $moduleClass;
		$this->moduleTable = $moduleTable;
		$this->moduleName = $name;

		$this->index = $index;
		$this->fields = false;
		if (is_array($fields)) {
			$this->fields = array();
			foreach ($fields as $field) {
				$this->fields[] = $field;
				if (is_a($field, 'tpt_ModuleField')) {
					$this->fieldsByName[$field->fieldName] = $field;
				}
			}
		}

		if (!empty($this->moduleTable) && !empty($this->fields)) {
			$this->moduleData = $vars['data'][$this->moduleTable] = array();

			$query = 'SELECT COUNT(*)AS `c` FROM `' . $this->moduleTable . '`';
			$vars['db']['handler']->query($query);
			$mysql_res = $vars['db']['handler']->fetch_assoc();

			$this->records = $mysql_res['c'];


			// prepare pagination
			$qrc = '';
			if (isset($_COOKIE[$this->moduleName]['queryRowCount'])) {
				$qrc = $_COOKIE[$this->moduleName]['queryRowCount'];
			}
			if (isset($_GET[$this->moduleName]['queryRowCount'])) {
				$qrc = $_GET[$this->moduleName]['queryRowCount'];
			}
			if (isset($_POST[$this->moduleName]['queryRowCount'])) {
				$qrc = $_POST[$this->moduleName]['queryRowCount'];
			}
			if ($qrc === '') {
				$this->queryRowCount = $vars['config']['module_pagination_default_limit'];
				//tpt_request::setcookie($vars, $this->moduleName . '[queryRowCount]', '', time() - 1, '/');
			} else {
				$qrc = min(intval($qrc, 10), $this->records);
				$qrc = strval($qrc);
				if ($qrc === '0') {
					$this->queryRowCount = 0;
					//tpt_request::setcookie($vars, $this->moduleName . '[queryRowCount]', '0', time() + 24 * 60 * 60 * 365, '/');
					//tpt_request::setcookie($vars, $this->moduleName . '[queryRowCount]', '0', time() - 1, '/');
				} else {
					$this->queryRowCount = $qrc;
					//tpt_request::setcookie($vars, $this->moduleName . '[queryRowCount]', $qrc, time() + 24 * 60 * 60 * 365, '/');
					//tpt_request::setcookie($vars, $this->moduleName . '[queryRowCount]', $qrc, time() - 1, '/');
				}
			}

			//var_dump($_COOKIE[$this->moduleName]['queryStart']);die();
			$qs = '1';
			if ($qrc === '0') {
				//tpt_request::setcookie($vars, $this->moduleName . '[queryStart]', '', time() - 1, '/');
			} else {
				if (isset($_COOKIE[$this->moduleName]['queryStart'])) {
					$qs = $_COOKIE[$this->moduleName]['queryStart'];
				}
				if (isset($_GET[$this->moduleName]['queryStart'])) {
					$qs = $_GET[$this->moduleName]['queryStart'];
				}
				if (isset($_POST[$this->moduleName]['queryStart'])) {
					$qs = $_POST[$this->moduleName]['queryStart'];
				}

				$qs = min(max(intval($qs, 10), 0), $this->records);
				//tpt_request::setcookie($vars, $this->moduleName . '[queryStart]', $qs, time() + 24 * 60 * 60 * 365, '/');
				//tpt_request::setcookie($vars, $this->moduleName . '[queryStart]', $qs, time() - 1, '/');
			}
			$this->queryStart = $qs;

			$this->queryRowCount = min($this->records, $this->queryRowCount);
			$this->currentPage = 1;
			$this->pages = 1;
			if (!empty($this->queryRowCount)) {
				$this->pages = intval(ceil($this->records / $this->queryRowCount), 10);
				if (!empty($this->queryStart) && (intval($this->queryStart, 10) !== 1)) {
					$this->currentPage = ceil($this->queryStart / $this->queryRowCount);
				}
			}
			$this->queryStart = ($this->currentPage - 1) * $this->queryRowCount + 1;

			$limit = '';
			//var_dump($this->queryRowCount);die();
			if ($qrc !== '0') {
				$limit .= ' LIMIT ';
				if (!empty($this->queryStart) && (intval($this->queryStart, 10) !== 1)) {
					$limit .= ($this->queryStart - 1) . ', ';
				}
				$limit .= $this->queryRowCount;
			}
			//var_dump($limit);die();

			/*
			if (!$vars['environment']['isAdmin']) {
				$limit = '';
			}
			*/
			$limit = '';

			if ($this->load_unindexed) {
				$query = 'SELECT * FROM `' . $this->moduleTable . '`' . $limit;
				$vars['db']['handler']->query($query);
				$vars['data'][$this->moduleTable]['unindexed'] = $vars['db']['handler']->fetch_assoc_list();
			}

			foreach ($this->fields as $field) {
				if (is_a($field, 'tpt_ModuleField')) {
					if ($field->index_data) {
						//tpt_dump($this->moduleTable);
						//if($field->fieldName == 'pname') {
						//	tpt_dump($field, true);
						//}
						$where = trim($field->queryRules);
						if(!empty($where)) {
							$where = ' AND '.$where;
						}
						$query = 'SELECT * FROM `' . $this->moduleTable . '` WHERE `' . $field->fieldName . '`>\'\' ' . $where;
						//if(!empty($where)) {
						//tpt_dump($query, true, 'R');
						//}
						$vars['db']['handler']->query($query);
						$vars['data'][$this->moduleTable][$field->fieldName] = $vars['db']['handler']->fetch_assoc_list($field->fieldName, $field->split_keys);

					}

				}
			}
			$this->moduleData = $vars['data'][$this->moduleTable];

		}
	}

	function getUpdateFormFields(&$vars, $index) {
		$ajax_call_update = tpt_ajax::getCall('module.update_row');
		$ajax_call_delete = tpt_ajax::getCall('module.delete_row');
		$formFields = '';
		if (!empty($this->moduleTable) && !empty($this->fields) && !empty($this->moduleData[$index])) {
			foreach ($this->moduleData[$index] as $ind => $data) {
				$formFields .= '<div class="moduleTableRow clearBoth margin-bottom-5" style="border: 1px solid #FFF;">';
				foreach ($this->fields as $field) {
					if (is_a($field, 'tpt_ModuleField')) {
						$formFields .= $field->get_control($vars, $this->moduleTable, $ind, $data[$field->fieldName]);
					} else {
						preg_match_all('#(`(.*?)`)#', $field, $mtch, PREG_SET_ORDER);
						if (!empty($mtch)) {
							foreach ($mtch as $m) {
								$rpl = '';
								foreach ($this->fields as $f) {
									if ($f->fieldName == $m[2]) {
										$rpl = $data[$m[2]];
										break;
									}
								}
								$field = str_replace($m[1], $rpl, $field);
							}
						}
						$formFields .= $field;
					}
				}
				$formFields .= '<div class="moduleTableUpdateWrapper float-left"><input type="button" value="Update row" onclick="' . $ajax_call_update . '" /></div>';
				$formFields .= '<div class="moduleTableDeleteWrapper float-left"><input type="button" value="Delete row" onclick="' . $ajax_call_delete . '" /></div>';
				$formFields .= '</div>';
				$formFields .= '<div class="height-5"></div>';
			}
		}

		return $formFields;
	}

	function getPagination(&$vars) {
		//var_dump($this->records);die();
		$pagination = '';
		//var_dump($this->moduleTable);die();
		if (!empty($this->moduleTable)) {
			$selectContentArray = array(array(1, 1));
			if (!empty($this->queryRowCount)) {
				if ($this->pages == 1) {
					$a = array(1);
					$b = array(1);
				} else {
					$a = range(1, $this->pages);
					$b = range(1, $this->records, $this->queryRowCount);
				}
				//var_dump($b);die();
				$selectContentArray = array_map(null, $b, $a);
			}
			//var_dump($selectContentArray);die();

			$pagesSelect = tpt_html::createSelect($vars, $this->moduleName . '[queryStart]', $selectContentArray, $this->currentPage - 1, ' autocomplete="off"');
			$ajax_call = tpt_ajax::getCall('pagination.refresh');

			$showingrows = '';
			$numrows = $this->queryRowCount ? $this->queryRowCount : $this->records;
			if (!$numrows) {
				$showingrows = '0';
			} else {
				$recordsStart = (($this->currentPage - 1) * $numrows + 1);
				$recordsEnd = min($this->currentPage * $numrows, $this->records);

				$showingrows = $recordsStart . '&nbsp;-&nbsp;' . $recordsEnd;
			}

			$pagination .= '<div class="clearBoth padding-top-20 padding-right-20 padding-down-20 padding-left-20 text-align-center">';
			$pagination .= '<h4 class="text-align-center color-white" style="margin: 0px;">Total records:&nbsp;' . $this->records . '</h4>';
			$pagination .= '<h4 class="text-align-center color-white" style="margin: 0px;">Showig records:&nbsp;' . $showingrows . '</h4>';
			$pagination .= '<div class="paginationParentNode"><input type="hidden" value="' . base64_encode($this->moduleName) . '" /><span>Start at page:&nbsp;</span>' . $pagesSelect . '<input onclick="tpt_selectPrevious(this.parentNode.parentNode.getElementsByTagName(\'SELECT\')[0]); ' . $ajax_call . '" type="button" value=" &lt; " /><input onclick="tpt_selectNext(this.parentNode.parentNode.getElementsByTagName(\'SELECT\')[0]); ' . $ajax_call . '" type="button" value=" &gt; " />';
			$pagination .= '<br />';
			$pagination .= '<span>Rows per page:&nbsp;</span><span><input class="width-60" autocomplete="off" onkeypress="return numbersonly(this, event);" oninput="tpt_getPages(this)" onpropertychange="tpt_getPages(this)" type="text" name="' . $this->moduleName . '[queryRowCount]" value="' . $this->queryRowCount . '" />(Clear the field to use the default limit - ' . $vars['config']['module_pagination_default_limit'] . '. Enter 0 for all records.)</span></div>';
			$pagination .= '<span><input type="button" value="Get range" onclick="' . $ajax_call . '" /></span>';
			$pagination .= '</div>';
		}

		//var_dump($pagination);die();
		return $pagination;
	}

	function getAddFormFields(&$vars, $index) {
		$ajax_call_add = tpt_ajax::getCall('module.add_row');
		$formFields = '';
		if (!empty($this->moduleTable) && !empty($this->fields)) {
			$formFields .= '<div class="moduleTableRow clearBoth margin-bottom-5" style="border: 1px solid #FFF;">';
			foreach ($this->fields as $field) {
				if (is_a($field, 'tpt_ModuleField')) {
					$formFields .= $field->get_control($vars, $this->moduleTable, count($this->moduleData[$index]), '');
				} else {
					preg_match_all('#(`(.*?)`)#', $field, $mtch, PREG_SET_ORDER);
					if (!empty($mtch)) {
						foreach ($mtch as $m) {
							$rpl = '';
							foreach ($this->fields as $f) {
								if ($f->fieldName == $m[2]) {
									$rpl = $data[$m[2]];
									break;
								}
							}
							$field = str_replace($m[1], $rpl, $field);
						}
					}
					$formFields .= $field;
				}
			}
			$formFields .= '<div class="moduleTableUpdateWrapper float-left"><input type="button" value="Add row" onclick="' . $ajax_call_add . '" /></div>';
			$formFields .= '</div>';
			$formFields .= '<div class="height-5"></div>';
		}

		return $formFields;
	}

}
