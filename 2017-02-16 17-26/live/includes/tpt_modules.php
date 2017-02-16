<?php

defined('TPT_INIT') or die('access denied');

class tpt_Modules {

    public $modules = array();
    public $modulesByTable;
    
    static $cache_enabled = 0;
    static $cache_load_modules = '';
    static $cache_skip_modules = '';
    
    function __construct(&$vars) {
		$query = 'SELECT * FROM `tpt_modules` WHERE `enabled`=1';
		$vars['db']['handler']->query($query);
		$vars['data']['tpt_modules']['unindexed'] = $vars['db']['handler']->fetch_assoc_list();
		$query = 'SELECT * FROM `tpt_modules` WHERE `enabled`=1';
		$vars['db']['handler']->query($query);
		$vars['data']['tpt_modules']['name'] = $vars['db']['handler']->fetch_assoc_list('name', false);

		$this->getCoreModules($vars);
    }
    
    function getCoreModules(&$vars) {


		if(is_array($vars['data']['tpt_modules']['name'])) {
			foreach($vars['data']['tpt_modules']['name'] as $name=>$module) {
				if(!empty($module['core'])) {
					$this->getModule($vars, $name);
				}
			}
		}

		//var_dump($this->modules['BandColor']);
		//return $this->modules;
	}
    function getModules(&$vars) {
		//tpt_dump('asddddddssssss', true);
		/*
        $query = 'SELECT * FROM `tpt_modules` WHERE `enabled`=1';
        $vars['db']['handler']->query($query, __FILE__);
        $vars['data']['tpt_modules']['unindexed'] = $vars['db']['handler']->fetch_assoc_list();
        $query = 'SELECT * FROM `tpt_modules` WHERE `enabled`=1';
        $vars['db']['handler']->query($query, __FILE__);
        $vars['data']['tpt_modules']['name'] = $vars['db']['handler']->fetch_assoc_list('name', false);
		*/

		//tpt_dump($vars['environment']['page_rule']['use_module_getter_function']);
		//tpt_dump($vars['environment']['page_rule']['cache_enabled']);
		//tpt_dump('asddddddssssss');
		//tpt_dump($vars['environment']['page_rule']);
		if(!empty($vars['environment']['page_rule']['use_module_getter_function'])) {
			//tpt_dump('asd', true);
		} else if(!empty($vars['environment']['page_rule']['cache_enabled'])) {
			//tpt_dump('asdddddd', true);
		} else {
			//tpt_dump('asddddddssssss');
			if (is_array($vars['data']['tpt_modules']['name'])) {
				foreach ($vars['data']['tpt_modules']['name'] as $name => $module) {
					if (empty($module['core'])) {
						$this->getModule($vars, $name);
					}
				}
			}
		}
		//tpt_dump(array_keys($this->modules), true);
        
        //var_dump($this->modules['BandColor']);
        //return $this->modules;
    }

	function getModule(&$vars, $name) {
		$moduleClassFile = TPT_MODULES_DIR . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . $name . '.php';
        $moduleClass = TPT_MODULE_CLASS_PREFIX . $name;
		$moduleTable = TPT_MODULE_CLASS_PREFIX . strtolower($name);
		if (is_file($moduleClassFile)) {
			ob_start();
			if (!class_exists($moduleClass)) {
				include($moduleClassFile);
			}
			if (class_exists($moduleClass)) {
				if ($module_instance = new $moduleClass($vars, $name, $moduleClassFile, $moduleClass, $moduleTable)) {
					if (is_subclass_of($module_instance, 'tpt_Module')) {
						$this->modules[$name] = $this->modulesByTable[$moduleTable] = $module_instance;
						return $module_instance;
					}
				}
			}
		}

		return false;
	}
    
    
    function rebuild(&$vars) {
        if(empty($vars['data']['tpt_modules']['name'])) {
            $vars['messages'][] = array('text'=>'No modules installed.', 'type'=>'warning');
            return;
        }

        if(!is_array($tables)) {
            $vars['messages'][] = array('text'=>'Database error.', 'type'=>'error');
            return;
        }
        $this->tables = array_combine($tables, $tables);
        $setup = 0;
        $updates = 0;
        $unchanged = 0;
        $error = 0;
        foreach($vars['data']['tpt_modules']['name'] as $name=>$module) {
            $this->installModule($vars, $name);
        }
        
    }
    
    function installModule(&$vars, $name) {
        $moduleClassFile = TPT_MODULES_DIR.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.$name.'.php'; 
        $moduleClass = TPT_MODULE_CLASS_PREFIX.$name;
        $moduleTable = TPT_MODULE_CLASS_PREFIX.strtolower($name);
        //var_dump($moduleTable);die();
        //var_dump($moduleClassFile);die();
        //var_dump($moduleClassFile);die();
        if(is_file($moduleClassFile)) {
            ob_start();
            if(!class_exists($moduleClass))
                include($moduleClassFile);
            if(class_exists($moduleClass)) {
                $module_instance = new $moduleClass($vars, $name, $moduleClassFile, $moduleClass, $moduleTable);
                if(is_subclass_of($module_instance, 'tpt_Module')) {
                    if(isset($tables[$moduleTable])) {
                        
                    } else {
                        if(!empty($module_instance->fields)) {
                            $primary_key = '';
                            $fdefs = array();
                            foreach($module_instance->fields as $field) {
                                if(is_a($field, 'tpt_ModuleField')) {
                                    $type = 'VARCHAR';
                                    $length = '(255)';
                                    $zerofill = '';
                                    $unsigned = '';
                                    $default = ' DEFAULT NULL';
                                    $autoincrement = '';
                                    switch(strtolower($field->fieldType)) {
                                        case 'n' :
                                            if($field->options == 'ai') {
                                                $type = 'INT';
                                                $length = '(10)';
                                                $zerofill = '';
                                                $unsigned = ' UNSIGNED';
                                                $default = ' NOT NULL';
                                                $autoincrement = ' AUTO_INCREMENT';
                                            } else if($field->options == 'vc') {
                                                $type = 'VARCHAR';
                                                $length = '(255)';
                                                $zerofill = '';
                                                $unsigned = '';
                                                $default = ' NOT NULL';
                                                $autoincrement = '';
                                            } else {
                                                $type = 'VARCHAR';
                                                $length = '(255)';
                                                $zerofill = '';
                                                $unsigned = '';
                                                $default = ' NOT NULL';
                                                $autoincrement = '';
                                            }
                                            if(empty($primary_key))
                                                $primary_key = ', PRIMARY KEY (`'.$field->fieldName.'`)';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            break;
                                        case 'i' :
                                            $type = 'INT';
                                            $length = '(11)';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.intval($field->dflt, 10).'\'';
                                            }
                                            break;
                                        case 'usi' : // unsigned small int
                                            $type = 'SMALLINT';
                                            $length = '(5)';
                                            $zerofill = '';
                                            $unsigned = ' UNSIGNED';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.intval($field->dflt, 10).'\'';
                                            }
                                            break;
                                        case 'ui' : // unsigned small int
                                            $type = 'INT';
                                            $length = '(11)';
                                            $zerofill = '';
                                            $unsigned = ' UNSIGNED';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.intval($field->dflt, 10).'\'';
                                            }
                                            break;
                                        case 'si' :
                                            $type = 'SMALLINT';
                                            $length = '(5)';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.intval($field->dflt, 10).'\'';
                                            }
                                            break;
                                        case 'ti' :
                                            $type = 'TINYINT';
                                            $length = '(1)';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                            }
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.intval($field->dflt, 10).'\'';
                                            }
                                            break;
                                        case 's' :
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(is_numeric($field->fieldLength)) {
                                                $length = '('.intval($field->fieldLength, 10).')';
                                                $type = 'VARCHAR';
                                            } else {
                                                $length = '';
                                                $type = 'TEXT';
                                            }
                                            if(!empty($field->dflt)) {
                                                $default = ' DEFAULT \''.$field->dflt.'\'';
                                            }
                                            break;
                                        case 'f' :
                                            $type = 'DOUBLE';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            $length = '';
                                            if(is_numeric($field->dflt)) {
                                                $default = ' DEFAULT \''.$field->dflt.'\'';
                                            }
                                            break;
                                        case 'date' :
                                            $type = 'DATE';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' NOT NULL';
                                            $autoincrement = '';
                                            $length = '';
                                            if(!empty($field->dflt)) {
                                                $default = ' DEFAULT \''.$field->dflt.'\'';
                                            }
                                            break;
                                        case 'b' :
                                            $type = 'TINYINT';
                                            $length = '(1)';
                                            $zerofill = '';
                                            $unsigned = '';
                                            $default = ' DEFAULT NULL';
                                            $autoincrement = '';
                                            if(!is_null($field->dflt)) {
                                                if($field->dflt) {
                                                    $default = ' DEFAULT \'1\'';
                                                } else {
                                                    $default = ' DEFAULT \'0\'';
                                                }
                                            }
                                            break;
                                    }
                                    $fdefs[] = '`'.$field->fieldName.'` '.$type.$length.$unsigned.$default.$autoincrement;
                                }
                            }
                            $fdefs_str = implode(', ', $fdefs).$primary_key;
                            if(!empty($fdefs_str)) {
                                $query  = '';
                                $query .= 'CREATE TABLE `'.$moduleTable.'` (';
                                $query .= $fdefs_str;
                                $query .= ') ENGINE=MYISAM DEFAULT CHARSET=utf8 ';
                                //var_dump($query);die();
                                $vars['db']['handler']->query($query, __FILE__);
                                
                                $vars['db']['tables'] = $vars['db']['handler']->get_tables();
                                if(is_array($vars['db']['tables']))
                                    $vars['db']['tables'] = array_combine($vars['db']['tables'], $vars['db']['tables']);
                                
                                if(isset($tables[$moduleTable])) {
                                    $vars['messages'][] = array('text'=>'Module installed successfully. Module ('.$name.')', 'type'=>'message');
                                } else {
                                    $vars['messages'][] = array('text'=>'Could not create module database table. Query unsuccessful. Module ('.$name.')', 'type'=>'error');
                                }
                            }
                        }
                    }
                } else {
                    
                }
            } else {
                $vars['messages'][] = array('text'=>'Module class file general failure. No such class ('.$name.')', 'type'=>'error');
            }
             
        } else {
            $vars['messages'][] = array('text'=>'Module class file not found. ('.$moduleClassFile.')', 'type'=>'error');
        }
        
        $query = 'SELECT `name` FROM `tpt_modules` WHERE `name`="'.mysql_real_escape_string($name).'"';
        $vars['db']['handler']->query($query, __FILE__);
        $module_remembered = $vars['db']['handler']->fetch_assoc_list();
        
        if(empty($module_remembered)) {
            $query = 'INSERT INTO `tpt_modules` (`name`) VALUES("'.mysql_real_escape_string($name).'")';
            $vars['db']['handler']->query($query, __FILE__);
            $module_remembered = $vars['db']['handler']->fetch_assoc_list();
        }
        
        $this->getModules($vars);
    }
    
    function beforeContent(&$vars) {
        $tpt_baseurl = $vars['config']['baseurl'];
        
        if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $taskComponent = false;
            //var_dump($vars['environment']['ajax_call']['task']['task']);die();
            if(!empty($vars['environment']['ajax_call']['task']['task'])) {
				$taskComponent = explode('.', $vars['environment']['ajax_call']['task']['task']);
			}
            if(/*!empty($taskComponent) && */(isset($_GET['install'])) /*|| (is_array($taskComponent) && (strtolower($taskComponent[0]) == 'module')))*/) {
                if($vars['user']['isLogged'] && ($vars['user']['data']['usertype'] == 3)) {
                    if(!empty($_GET['install'])) {
                        preg_match_all('#[a-zA-Z0-9_]#', $_GET['install'], $mtch, PREG_PATTERN_ORDER);
                        //$moduleName = trim($_GET['install']);
                        if(!empty($mtch[0])) {
                            $moduleName = implode($mtch[0]);
                            //var_dump($moduleName);die();
                            $this->installModule($vars, $moduleName);
                            
                            $vars['db']['tables'] = $vars['db']['handler']->get_tables();
                        }
                    } else {
                        //var_dump($vars['environment']['ajax_call']['task']);die();
                        switch(strtolower($vars['environment']['ajax_call']['task']['task'])) {
                            case 'module.update_row' :
                                if(!empty($_POST['tpt_modules'])) {
                                    $moduleTables = $_POST['tpt_modules'];
                                    foreach($_POST['tpt_modules'] as $table=>$rows) {
                                        if(isset($vars['db']['tables'][$table]) && isset($this->modulesByTable[$table])) {
                                            //var_dump($table);die();
                                            //var_dump($this->modulesByTable);die();
                                            $module = $this->modulesByTable[$table];
                                            //var_dump($module->fields);die();
                                            foreach($rows as $id=>$data) {
                                                $updates = array();
                                                $after = array();
                                                foreach($module->fieldsByName as $fieldName=>$field) {
                                                    if(isset($data[$fieldName]))
                                                        switch(strtolower($field->fieldType)) {
                                                            case 's' :
                                                                preg_match_all('#(\$\{(.*?)\})#', $field->storageOptions, $mtch, PREG_SET_ORDER);
                                                                if(!empty($mtch)) {
                                                                    $after[] = $field;
                                                                } else {
                                                                    $sopts = explode(',', $field->storageOptions);
                                                                    foreach($sopts as $sopt) {
                                                                        switch(strtolower($sopt)) {
                                                                            case '' :
                                                                            break;
                                                                            case 'floatval' :
                                                                                $data[$fieldName] = floatval($data[$fieldName]);
                                                                                break;
                                                                            default :
                                                                            case 'intval10' :
                                                                                $data[$fieldName] = intval($data[$fieldName], 10);
                                                                                break;
                                                                            default :
                                                                            break;
                                                                        }
                                                                    }
                                                                    $updates[] = '`'.$fieldName.'`="'.$data[$fieldName].'"';
                                                                }
                                                                break;
                                                            case 'si' :
                                                            case 'f' :
                                                            case 'i' :
                                                                $sopts = explode(',', $field->storageOptions);
                                                                foreach($sopts as $sopt) {
                                                                    switch(strtolower($sopt)) {
                                                                        case '' :
                                                                        break;
                                                                        case 'intval10' :
                                                                            $data[$fieldName] = intval($data[$fieldName], 10);
                                                                            break;
                                                                        case 'floatval' :
                                                                            $data[$fieldName] = floatval($data[$fieldName]);
                                                                            break;
                                                                        default :
                                                                        break;
                                                                    }
                                                                }
                                                                $updates[] = '`'.$fieldName.'`='.$data[$fieldName].'';
                                                                break;
                                                        }
                                                }
                                                
                                                foreach($after as $field) {
                                                    $fieldName = $field->fieldName;
                                                    
                                                    $val = $field->storageOptions;
                                                    preg_match_all('#(\$\{(.*?)\})#', $val, $mtch, PREG_SET_ORDER);
                                                    $rep = '';
                                                    
                                                    //var_dump($mtch);die();
                                                    foreach($mtch as $msegment) {
                                                        preg_match('#(([^(]*)\(([^(]*?)\))#', $msegment[2], $mtch2);
                                                        while(!empty($mtch2)) {
                                                            
                                                            $raw_params = explode(',',$mtch2[3]);
                                                            $params = array();
                                                            foreach($raw_params as $rp) {
                                                                $param = trim($rp);
                                                                if($raw_params[0] == '$') {
                                                                    $params[] = ${substr($param, 1)};
                                                                } else if(($param[0] == '"')||($param[0] == '\'')) {
                                                                    $params[] = substr($param, 1, strlen($param)-2);
                                                                } else if($param[0] == '`') {
                                                                    if(isset($data[substr($param, 1, strlen($param)-2)])) {
                                                                        $params[] = $data[substr($param, 1, strlen($param)-2)];
                                                                    } else {
                                                                        $params[] = '';
                                                                    }
                                                                } else if(defined($param)) {
                                                                    $params[] = constant($param);
                                                                } else {
                                                                    $params[] = $param;
                                                                }
                                                            }
                                                            //var_dump($msegment[2]);die();
                                                            //var_dump($mtch2[1]);die();
                                                            //var_dump($mtch2[2]);die();
                                                            //if($mtch2[2] == 'str_pad') {
                                                                //var_dump($params);die();
                                                            //}
                                                            //var_dump('"'.call_user_func_array($mtch2[2], $params), $msegment[2]).'"');die();
                                                            $msegment[2] = $rep = str_replace($mtch2[1], '"'.call_user_func_array($mtch2[2], $params).'"', $msegment[2]);
                                                            //var_dump($rep);die();
                                                            preg_match('#(([^(]*)\(([^(]*?)\))#', $msegment[2], $mtch2);
                                                        }
                                                        //var_dump($msegment[1]);die();
                                                        //var_dump($rep);die();
                                                        //var_dump($val);die();
                                                        if($rep[0] == '"')
                                                            $rep = substr($rep, 1, strlen($rep)-2);
                                                        $val = str_replace($msegment[1], $rep, $val);
                                                    }
                                                        
                                                    //var_dump($val);die();
                                                    
                                                    $data[$fieldName] = $val;
                                                    $updates[] = '`'.$fieldName.'`="'.mysql_real_escape_string($data[$fieldName]).'"';
                                                }
                                                if(!empty($updates)) {
                                                    $updates = implode(', ', $updates);
                                                    $query  = '';
                                                    $query .= 'UPDATE `'.$table.'` SET '.$updates.' WHERE `'.$module->index.'`='.$id;
                                                    //var_dump($query);die();
                                                    $vars['db']['handler']->query($query, __FILE__);
                                                    $vars['environment']['ajax_result']['messages'][] = array('Row updated. ID: '.$id, 'message');
                                                    //var_dump($query);die();
                                                }
                                            }
                                        } 
                                    }
                                }
                            break;
                            case 'module.add_row' :
                                if(!empty($_POST['tpt_modules'])) {
                                    $moduleTables = $_POST['tpt_modules'];
                                    foreach($_POST['tpt_modules'] as $table=>$rows) {
                                        if(isset($vars['db']['tables'][$table]) && isset($this->modulesByTable[$table])) {
                                            //var_dump($table);die();
                                            //var_dump($this->modulesByTable);die();
                                            $module = $this->modulesByTable[$table];
                                            //var_dump($module->fields);die();
                                            foreach($rows as $id=>$data) {
                                                $flds = array();
                                                $vals = array();
                                                $after = array();
                                                foreach($module->fieldsByName as $fieldName=>$field) {
                                                    if(isset($data[$fieldName]))
                                                        switch(strtolower($field->fieldType)) {
                                                            case 's' :
                                                                preg_match_all('#(\$\{(.*?)\})#', $field->storageOptions, $mtch, PREG_SET_ORDER);
                                                                if(!empty($mtch)) {
                                                                    $after[] = $field;
                                                                } else {
                                                                    $sopts = explode(',', $field->storageOptions);
                                                                    foreach($sopts as $sopt) {
                                                                        switch(strtolower($sopt)) {
                                                                            case '' :
                                                                            break;
                                                                            case 'intval10' :
                                                                                $data[$fieldName] = intval($data[$fieldName], 10);
                                                                                break;
                                                                            default :
                                                                            break;
                                                                        }
                                                                    }
                                                                    $flds[] = '`'.$fieldName.'`';
                                                                    $vals[] = '"'.mysql_real_escape_string($data[$fieldName]).'"';
                                                                }
                                                            break;
                                                            case 'i' :
                                                            case 'si' :
                                                            case 'f' :
                                                                $sopts = explode(',', $field->storageOptions);
                                                                foreach($sopts as $sopt) {
                                                                    switch(strtolower($sopt)) {
                                                                        case '' :
                                                                        break;
                                                                        case 'intval10' :
                                                                            $data[$fieldName] = intval($data[$fieldName], 10);
                                                                            break;
                                                                        default :
                                                                        case 'floatval' :
                                                                            $data[$fieldName] = floatval($data[$fieldName]);
                                                                            break;
                                                                        default :
                                                                        default :
                                                                        break;
                                                                    }
                                                                }
                                                                $flds[] = '`'.$fieldName.'`';
                                                                $vals[] = '"'.mysql_real_escape_string($data[$fieldName]).'"';
                                                            break;
                                                        }
                                                }
                                                
                                                
                                                foreach($after as $field) {
                                                    $fieldName = $field->fieldName;
                                                    
                                                    $val = $field->storageOptions;
                                                    preg_match_all('#(\$\{(.*?)\})#', $val, $mtch, PREG_SET_ORDER);
                                                    $rep = '';
                                                    
                                                    foreach($mtch as $msegment) {
                                                        preg_match('#(([^(]*)\(([^(]*?)\))#', $msegment[2], $mtch2);
                                                        while(!empty($mtch2)) {
                                                            
                                                            $raw_params = explode(',',$mtch2[3]);
                                                            $params = array();
                                                            foreach($raw_params as $rp) {
                                                                $param = trim($rp);
                                                                if($raw_params[0] == '$') {
                                                                    $params[] = ${substr($param, 1)};
                                                                } else if(($param[0] == '"')||($param[0] == '\'')) {
                                                                    $params[] = substr($param, 1, strlen($param)-2);
                                                                } else if($param[0] == '`') {
                                                                    if(isset($data[substr($param, 1, strlen($param)-2)])) {
                                                                        $params[] = $data[substr($param, 1, strlen($param)-2)];
                                                                    } else {
                                                                        $params[] = '';
                                                                    }
                                                                } else if(defined($param)) {
                                                                    $params[] = constant($param);
                                                                } else {
                                                                    $params[] = $param;
                                                                }
                                                            }
                                                            //var_dump($msegment[2]);die();
                                                            //var_dump($mtch2[1]);die();
                                                            //var_dump($mtch2[2]);die();
                                                            //if($mtch2[2] == 'str_pad') {
                                                                //var_dump($params);die();
                                                            //}
                                                            //var_dump('"'.call_user_func_array($mtch2[2], $params), $msegment[2]).'"');die();
                                                            $msegment[2] = $rep = str_replace($mtch2[1], '"'.call_user_func_array($mtch2[2], $params).'"', $msegment[2]);
                                                            //var_dump($rep);die();
                                                            preg_match('#(([^(]*)\(([^(]*?)\))#', $msegment[2], $mtch2);
                                                        }
                                                        //var_dump($msegment[1]);die();
                                                        //var_dump($rep);die();
                                                        //var_dump($val);die();
                                                        if($rep[0] == '"')
                                                            $rep = substr($rep, 1, strlen($rep)-2);
                                                        $val = str_replace($msegment[1], $rep, $val);
                                                    }
                                                        
                                                    //var_dump($val);die();
                                                    
                                                    $data[$fieldName] = $val;
                                                    $flds[] = '`'.$fieldName.'`';
                                                    $vals[] = '"'.mysql_real_escape_string($data[$fieldName]).'"';
                                                }
                                                if(!empty($flds)) {
                                                    $flds = '('.implode(', ', $flds).')';
                                                    $vals = 'VALUES('.implode(', ', $vals).')';
                                                    $query  = '';
                                                    $query .= 'INSERT INTO `'.$table.'` '.$flds.' '.$vals;
                                                    $vars['db']['handler']->query($query, __FILE__);
                                                    $vars['environment']['ajax_result']['messages'][] = array('Row added. ID: '.$vars['db']['handler']->last_id(), 'message');
                                                    //var_dump($query);die();
                                                }
                                            }
                                        } 
                                    }
                                }
                            break;
                            case 'module.delete_row' :
                                if(!empty($_POST['tpt_modules'])) {
                                    $moduleTables = $_POST['tpt_modules'];
                                    foreach($_POST['tpt_modules'] as $table=>$rows) {
                                        if(isset($vars['db']['tables'][$table]) && isset($this->modulesByTable[$table])) {
                                            //var_dump($table);die();
                                            //var_dump($this->modulesByTable);die();
                                            $module = $this->modulesByTable[$table];
                                            //var_dump($module->fields);die();
                                            foreach($rows as $id=>$data) {
                                                $query  = '';
                                                $query .= 'DELETE FROM `'.$table.'` WHERE `'.$module->index.'`='.$id;
                                                $vars['db']['handler']->query($query, __FILE__);
                                                $vars['environment']['ajax_result']['messages'][] = array('Row deleted. ID: '.$id, 'message');
                                                //var_dump($query);die();
                                            }
                                        } 
                                    }
                                }
                            break;
                        }
                        $this->getModules($vars);
                    }
                } else {
                    $vars['environment']['ajax_result']['messages'][] = array('You must be logged in to be able to edit the modules data.', 'error');
                }
            }
            
        }
        
        /*
        if($vars['environment']['isAdmin'] && $vars['user']['isLogged']) {
            foreach($this->modules as $name=>$module) {
                //var_dump($module);die();
                $mcont  = '';
                $mcont .= $module->getUpdateFormFields($vars, $module->index);
                $mcont .= $module->getAddFormFields($vars, $module->index);
                $vars['admin']['handler']->tabs[] = new tpt_adminTab($vars, $name, $mcont, $module->getPagination($vars));
                
            }
        }
        */
    }

    /*
    function afterContent(&$vars) {
        //echo '<pre>';
        //var_dump($this->modules);
        //echo '</pre>';
        //var_dump($vars['user']['isLogged']);die();
        foreach($this->modules as $name=>$module) {
            $vars['admin']['template_data']['admin_tabs'][base64_encode($name)] = array('title'=>$name, 'records'=>$module->records);
        }
    }
    */
    
}


