<?php

defined('TPT_INIT') or die('access denied');

class tpt_module_UrlRules extends tpt_Module {
    
    function __construct(&$vars, $name, $moduleClassFile, $moduleClass, $moduleTable) {
        $fields = array(
                //db field name|field type|length|options|storage options|control|ctrAttr|default|label|index by|split keys|template
            new tpt_ModuleField('id',    'n', null, 'ai', '',         'sp', '', '', '',           true, false,  'LC'),
            new tpt_ModuleField('url_preg_pattern',  's', '',  '',   '',         'tf', ' style="width: 70px;"', '', 'Url Pattern', false, false, 'LC'),
            new tpt_ModuleField('is_404',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'force 404', false, false, 'LC'),
            new tpt_ModuleField('is_414',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'force 414', false, false, 'LC'),
            new tpt_ModuleField('is_ajax',  's', 20,  '',   'intval10',         'tf', ' style="width: 70px;"', '0', 'Is Ajax', false, false, 'LC'),
            new tpt_ModuleField('is_redirect',  's', 20,  '',   'intval10',         'tf', ' style="width: 70px;"', '0', 'Is Redirect', false, false, 'LC'),
            new tpt_ModuleField('redirect_url',   's', 512,   '',   '', 'tf', ' style="width: 230px;"', '', 'Redirect URL', false, false, 'LC'),
            new tpt_ModuleField('include_file',   's', 80,   '',   '', 'tf', ' style="width: 230px;"', '', 'Include file', false, false, 'LC'),
            new tpt_ModuleField('dev_include_file',   's', 80,   '',   '', 'tf', ' style="width: 230px;"', '', 'Dev Include file', false, false, 'LC'),
            new tpt_ModuleField('include_file_old',   's', 80,   '',   '', 'tf', ' style="width: 230px;"', '', 'Old Include file', false, false, 'LC'),
            new tpt_ModuleField('html_title',   's', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'HTML Title', false, false, 'LC'),
            new tpt_ModuleField('html_meta_tags',   's', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'HTML Meta Tags', false, false, 'LC'),
            new tpt_ModuleField('html_head_content',   's', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'HTML Head Content', false, false, 'LC'),
            new tpt_ModuleField('left_bar',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '1', 'Left Bar', false, false, 'LC'),
            new tpt_ModuleField('google_tag_manager',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '1', 'Load the google tag manager code after <body>', false, false, 'LC'),
            new tpt_ModuleField('social_bar',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '1', 'Social Bar', false, false, 'LC'),
            new tpt_ModuleField('use_mobile_template',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Switch to Responsive Design on a Mobile Device', false, false, 'LC'),
            new tpt_ModuleField('use_mobile_template_dev',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Switch to Responsive Design on a Mobile Device isDev(use_mobile_template_dev)', false, false, 'LC'),
            new tpt_ModuleField('process_url',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Process Url', false, false, 'LC'),
            new tpt_ModuleField('sustain_url',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '1', 'Sustain Url', false, false, 'LC'),
            new tpt_ModuleField('url_match_type',   's', 32,   '',   '', 'tf', ' style="width: 230px;"', 'rpath', 'Url Match Type', false, false, 'LC'),
            new tpt_ModuleField('added_by',   's', 255,   '',   '', 'tf', ' style="width: 230px;"', 'dflt', 'Added By', false, false, 'LC'),
            new tpt_ModuleField('login_return_url',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '1', 'Login Return Url', false, false, 'LC'),
            new tpt_ModuleField('logout_return_url',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Logout Return Url', false, false, 'LC'),
            new tpt_ModuleField('continue_shopping_url',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Continue Shopping Url', false, false, 'LC'),
            new tpt_ModuleField('standard_url',   's', 255,   '',   '', 'tf', ' style="width: 230px;"', '', 'Standard URL', false, false, 'LC'),
            new tpt_ModuleField('priority',  'f', '',    '',   '', 'tf', ' style="width: 70px;"', '', 'Priority',       false, false, 'LC'),
            new tpt_ModuleField('cache_enabled',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Cache Enabled?', false, false, 'LC'),
            new tpt_ModuleField('tpt_cache_content_id',   'i', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'tpt_cache_content ID', false, false, 'LC'),
            new tpt_ModuleField('cache_load_modules',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Cache Load Modules', false, false, 'LC'),
            new tpt_ModuleField('cache_skip_modules',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Cache Skip Modules', false, false, 'LC'),
			new tpt_ModuleField('use_module_getter_function',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Use Module Getter Function', false, false, 'LC'),
			new tpt_ModuleField('access_level',   'i', '',   '',   '', 'tf', ' style="width: 230px;"', '0', 'Access Level', false, false, 'LC'),
            new tpt_ModuleField('enabled',   'ti', '',   '',   '', 'tf', ' style="width: 230px;"', '', 'Enabled?', false, false, 'LC'),
        );

        $moduleTable = 'tpt_module_urlrules';
        if(defined('TPT_ADMIN')) {
            //tpt_dump('asdasdasasd', true);
            $moduleTable = 'tpt_module_urlrules_administration';
        }
        //tpt_dump('asdas');

        parent::__construct($vars, $name, $moduleClassFile, $moduleClass, $moduleTable, $fields, 'id');
    }
    
    
    function parseRequestURL(&$vars, $table/*, &$evars*/) {
        global $tpt_vars;
        //tpt_dump($table, true);

		//tpt_dump($vars['url']['upath']);
        $upath = substr($vars['url']['upath'], 1);
		//tpt_dump($upath);
        $path = substr($vars['url']['path'], 1);
        
        return $this->parseURL($vars, $table, $upath/*, $evars*/);
        //$this->parseURL($vars, $path, $evars);
    }
    
    
    function parseURL(&$vars, $table, $url/*, &$evars*/) {
        //$_tbl = $table;
        //tpt_dump($_tbl);
        //tpt_dump($table);
        //tpt_dump($url, true);
        global $tpt_vars;
        //extract($evars);
        //$purl = parse_url($url);
        //$table = $this->moduleTable;
        //tpt_dump($_tbl);
        //tpt_dump($table, true);
        $res = array(
            'status'=>false,
            'murls'=>array(),
            'include_pre'=>array(),
            'include_main'=>array(),
            'include_post'=>array()
        );
        
        
        $query = <<< EOT
SELECT * FROM
(

    SELECT
        *,
        1 AS `mtype`,
        1 AS `stype`,
        1 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`=0
        AND
        `process_url`=0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) LIKE "% i"
    
UNION

    SELECT
        *,
        1 AS `mtype`,
        1 AS `stype`,
        0 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP BINARY REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`=0
        AND
        `process_url`=0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) NOT LIKE "% i"

UNION

    SELECT
        *,
        1 AS `mtype`,
        1 AS `stype`,
        1 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`>0
        AND
        `process_url`=0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) LIKE "% i"
        
UNION

    SELECT
        *,
        1 AS `mtype`,
        1 AS `stype`,
        0 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP BINARY REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`>0
        AND
        `process_url`=0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) NOT LIKE "% i"
        
UNION

    SELECT
        *,
        2 AS `mtype`,
        2 AS `stype`,
        1 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`=0
        AND
        `process_url`>0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) LIKE "% i"
        
UNION

    SELECT
        *,
        2 AS `mtype`,
        2 AS `stype`,
        0 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP BINARY REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`=0
        AND
        `process_url`>0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) NOT LIKE "% i"
    
UNION

SELECT * FROM (
    SELECT
        *,
        2 AS `mtype`,
        2 AS `stype`,
        1 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`>0
        AND
        `process_url`>0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) LIKE "% i"
        
UNION

    SELECT
        *,
        2 AS `mtype`,
        2 AS `stype`,
        0 AS `cs`
    FROM
        `$table`
    WHERE
        `enabled`=1
        AND
        "$url" REGEXP BINARY REPLACE(TRIM(REPLACE(`url_preg_pattern`, "#", " ")), " i", "")
        AND
        `is_ajax`>0
        AND
        `process_url`>0
        AND
        TRIM(REPLACE(`url_preg_pattern`, "#", " ")) NOT LIKE "% i"
) AS `a`
ORDER BY `priority` ASC
        
) AS `a`
EOT;

        $vars['db']['handler']->query($query);
        $murls = $vars['db']['handler']->fetch_assoc_list('mtype', true);
        //tpt_dump($query);
        //tpt_dump($murls, true);
        tpt_logger::dump($vars, $url, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'URL', __FILE__.' '.__LINE__);
        tpt_logger::dump($vars, $query, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'URLQUERY', __FILE__.' '.__LINE__);
        tpt_logger::dump($vars, $murls, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 'MURLS', __FILE__.' '.__LINE__);

        $mrule = array();
        if(!empty($murls[1])) {
            //tpt_dump($rule, true);
            $vars['environment']['page_rule'] = $mrule = reset($murls[1]);
        }
        //tpt_dump($mrule, true);


        return array(
            'mrule'=>$mrule,
            'murls'=>$murls
        );

    }


    function includeRequestRuleFiles(&$vars, $murls, &$evars, $page_path = TPT_PAGES_DIR, $proc_path = TPT_PROC_DIR) {
        global $tpt_vars;
        //extract($evars);
        //tpt_dump($murls, true);

		$vars['stats']['memory_usage']['before_content_processors_includes'] = intval(memory_get_usage(), 10);
        if(!empty($murls[2])) {
            foreach($murls[2] as $rule) {
                $vars['_temp']['rule_data'] = $rule;

                if(empty($rule['is_ajax'])) {
                    $fvars = tpt_functions::f_include_urlrule_ajax_file($vars, $rule, $evars, $proc_path);
                } else {
                    ob_start();
                    $fvars = tpt_functions::f_include_urlrule_ajax_file($vars, $rule, $evars, $proc_path);
                    $vars['environment']['ajax_response'] = ob_get_contents();
                    ob_end_clean();
                    $vars['environment']['isAjax'] = true;
                }
            }

        }
		$vars['stats']['memory_usage']['after_content_processors_includes'] = intval(memory_get_usage(), 10);

    }

    function includeRequestRuleMainFile(&$vars, $mrule, &$evars, $page_path = TPT_PAGES_DIR, $proc_path = TPT_PROC_DIR) {
        global $tpt_vars;
        //extract($evars);
        //tpt_dump($vars['environment']['url_processors'], true);

        //tpt_dump($status);

		$vars['stats']['memory_usage']['before_before_content_processors'] = intval(memory_get_usage(), 10);
        foreach($vars['environment']['url_processors'] as $url_processor) {
            if(is_object($url_processor) && method_exists($url_processor, 'beforeContent')) {
                $url_processor->beforeContent($vars); // plugin logic before content
            }
        }
		$vars['stats']['memory_usage']['after_before_content_processors'] = intval(memory_get_usage(), 10);

		//tpt_dump($mrule);
        //************ INCLUDE PAGE SCRIPTS OR 404.php
		$vars['stats']['memory_usage']['before_main_include'] = intval(memory_get_usage(), 10);
        $fvars = array();
        if(!empty($vars['environment']['is404']) || !empty($vars['environment']['force404'])) {
            $fvars = tpt_functions::f_include_404_page_file($vars, $evars);
        } else if(!empty($vars['environment']['is414']) || !empty($vars['environment']['force414'])) {
			$fvars = tpt_functions::f_include_414_page_file($vars, $evars);
		} else if(!empty($vars['environment']['isRedirect'])) {
            $fvars = tpt_functions::f_include_redirect_page_file($vars, $evars);
        } else  if(!empty($mrule['is_redirect'])) {
			//$redirect_url = $vars['url']['handler']->wrap($vars, '/login-register', true, 2);
			tpt_request::base_redirect($vars, $mrule['redirect_url']);
			//die($mrule['redirect_url']);
        } else {
            if (empty($mrule['is_ajax'])) {
                //$evars = tpt_functions::f_get_defined_vars($vars, get_defined_vars());
				//tpt_dump('asd');
                $fvars = tpt_functions::f_include_urlrule_page_file($vars, $mrule, $evars, $page_path);
                //extract($fvars);
            } else {
                $fvars = tpt_functions::f_include_urlrule_ajax_file($vars, $mrule, $evars, $proc_path);
            }
        }
		$vars['stats']['memory_usage']['after_main_include'] = intval(memory_get_usage(), 10);

        //die('aaaa');
        //tpt_dump('ssssss');
		//tpt_dump($vars['environment']['url_processors'], true);
		$vars['stats']['memory_usage']['before_after_content_processors'] = intval(memory_get_usage(), 10);
        foreach($vars['environment']['url_processors'] as $url_processor) { // include plugin afterContent methods
            if (is_object($url_processor) && method_exists($url_processor, 'afterContent')) {
                //tpt_dump(get_class($url_processor));
				//if(is_a($url_processor, 'tpt_logger')) {
				//	tpt_dump('logging', true);
				//}
                $url_processor->afterContent($vars); // plugin logic after content
            }
        }
		$vars['stats']['memory_usage']['after_after_content_processors'] = intval(memory_get_usage(), 10);


        $exec404 = false;
        $exec414 = false;
        if(defined('FORCE_404') || (defined('AMZ_IS_DEV') && !in_array($vars['user']['client_ip'], $vars['config']['allowed_dev_ips']))) {
            if(empty($mrule['is_ajax'])) {
                tpt_request::base_404($tpt_vars);

                $fvars = tpt_functions::f_include_404_page_file($vars, $vars['environment']['page_rule'], $evars); // override main include
            } else {
                die('404');
            }
        } else if(defined('FORCE_414')) {
			if(empty($mrule['is_ajax'])) {
				tpt_request::base_414($tpt_vars);

				$fvars = tpt_functions::f_include_414_page_file($vars, $vars['environment']['page_rule'], $evars); // override main include
			} else {
				die('414');
			}
		}


        return $fvars;
    }
    function includeAdminRequestRuleMainFile(&$vars, $mrule, &$evars, $page_path = TPT_PAGES_DIR, $proc_path = TPT_PROC_DIR) {
        global $tpt_vars;
        //extract($evars);
        //tpt_dump($vars['environment']['url_processors'], true);

        //tpt_dump($status);

        foreach($vars['environment']['admin_url_processors'] as $url_processor) {
            if(is_object($url_processor) && method_exists($url_processor, 'beforeContent')) {
                $url_processor->beforeContent($vars); // plugin logic before content
            }
        }

		//tpt_dump($mrule);
        //************ INCLUDE PAGE SCRIPTS OR 404.php
        $fvars = array();
        if(!empty($vars['environment']['is404']) || !empty($vars['environment']['force404'])) {
            $fvars = tpt_functions::f_include_404_page_file($vars, $evars);
        } else if(!empty($vars['environment']['is414']) || !empty($vars['environment']['force414'])) {
			$fvars = tpt_functions::f_include_414_page_file($vars, $evars);
		} else if(!empty($vars['environment']['isRedirect'])) {
            $fvars = tpt_functions::f_include_redirect_page_file($vars, $evars);
        } else  if(!empty($mrule['is_redirect'])) {
			//$redirect_url = $vars['url']['handler']->wrap($vars, '/login-register', true, 2);
			tpt_request::base_redirect($vars, $mrule['redirect_url']);
			//die($mrule['redirect_url']);
        } else {
            if (empty($mrule['is_ajax'])) {
                //$evars = tpt_functions::f_get_defined_vars($vars, get_defined_vars());
                $fvars = tpt_functions::f_include_urlrule_page_file($vars, $mrule, $evars, $page_path);
                //extract($fvars);
            } else {
                $fvars = tpt_functions::f_include_urlrule_ajax_file($vars, $mrule, $evars, $proc_path);
            }
        }

        //die('aaaa');
        //tpt_dump('ssssss');
		//tpt_dump($vars['environment']['url_processors'], true);
        foreach($vars['environment']['admin_url_processors'] as $url_processor) { // include plugin afterContent methods
            if (is_object($url_processor) && method_exists($url_processor, 'afterContent')) {
                //tpt_dump(get_class($url_processor));
				//if(is_a($url_processor, 'tpt_logger')) {
				//	tpt_dump('logging', true);
				//}
                $url_processor->afterContent($vars); // plugin logic after content
            }
        }


        $exec404 = false;
        if(defined('FORCE_404') || (defined('AMZ_IS_DEV') && !in_array($vars['user']['client_ip'], $vars['config']['allowed_dev_ips']))) {
            if(empty($mrule['is_ajax'])) {
                tpt_request::base_404($tpt_vars);

                $fvars = tpt_functions::f_include_404_page_file($vars, $vars['environment']['page_rule'], $evars); // override main include
            } else {
                die();
            }
        }


        return $fvars;
    }

    
    
    function parseRequestURL_Old(&$vars, &$evars) {
        global $tpt_vars;
        extract($evars);
        
        foreach($tpt_vars['data']['tpt_module_urlrules']['id'] as $mode=>$r) {
            $break = false;
            if($mode == 'process') {
                // initialze modules
                $tpt_vars['modules']['handler'] = $tpt_vars['environment']['url_processors'][] = new tpt_Modules($tpt_vars);
                amz_cart::init($tpt_vars);
                $tpt_vars['environment']['url_processors'][] = new tpt_cart_controller($tpt_vars);
                
                $urlrules_module = getModule($tpt_vars, "UrlRules");
                $urlrules_module->parseRequestURL($tpt_vars);
        
                ob_start();
                include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'header.php');
                $tpt_vars['template']['header'] = ob_get_contents();
                ob_end_clean();
        
                ob_start();
                include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'social-media-bar.php');
                $tpt_vars['template']['social_bar'] = ob_get_contents();
                ob_end_clean();
                //var_dump($tpt_vars['environment']['url_processors']);die();
            }
        
            //tpt_dump('asdasdasdasd');
            //$asd = get_defined_vars();
            //tpt_dump(array_keys($asd), true);
            //tpt_dump(array_keys($asd['GLOBALS']), true);
            foreach($r as $rule) {
                if($break)
                    break;
                switch($mode) {
        
                    case 'process' : // add auxillary scripts for modules or additional page logic
                        $matched = false;
        
                        if($rule['url_match_type'] == 'urpath') {
                            preg_match($rule['url_preg_pattern'], $tpt_vars['url']['upath'], $mtch);
                            if(!empty($mtch)) {
                                $matched = true;
        
                                if(!$rule['sustain_url']) {
                                    $af_byreg = $rule['url_preg_pattern'];
                                    $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                    $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                }
        
                                $tpt_vars['_temp']['rule_data'] = $rule;
                                if(!$rule['is_ajax']) {
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                } else {
                                    ob_start();
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                    $tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                    ob_end_clean();
                                    $tpt_vars['environment']['isAjax'] = true;
                                }
                            }
                        } else if($rule['url_match_type'] == 'rpath') {
                            preg_match($rule['url_preg_pattern'], $tpt_vars['url']['path'], $mtch);
                            if(!empty($mtch)) {
                                $matched = true;
        
                                if(!$rule['sustain_url']) {
                                    $af_byreg = $rule['url_preg_pattern'];
                                    $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                    $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                }
        
                                $tpt_vars['_temp']['rule_data'] = $rule;
                                if(!$rule['is_ajax']) {
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                } else {
                                    ob_start();
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                    $tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                    ob_end_clean();
                                    $tpt_vars['environment']['isAjax'] = true;
                                }
                            }
                        } else if($rule['url_match_type'] == 'path') {
                            if(strstr($tpt_vars['url']['path'], $rule['url_preg_pattern']) !== false) {
                                $matched = true;
        
                                if(!$rule['sustain_url']) {
                                    $af_byreg = $rule['url_preg_pattern'];
                                    $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                    $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                }
        
                                $tpt_vars['_temp']['rule_data'] = $rule;
                                if(!$rule['is_ajax']) {
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                } else {
                                    ob_start();
                                    //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                    if(isDev('includewrappers')) {
                                        $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                        $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                        extract($fvars);
                                    } else {
                                        include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                    }
                                    $tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                    ob_end_clean();
                                    $tpt_vars['environment']['isAjax'] = true;
                                }
                            }
                        } else if($rule['url_match_type'] == 'segments') {
                            foreach($tpt_vars['url']['bpath'] as $segment) {
                                if(strstr($segment, $rule['url_preg_pattern']) !== false) {
                                    $matched = true;
        
                                    if(!$rule['sustain_url']) {
                                        $af_byreg = $rule['url_preg_pattern'];
                                        $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                        $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                    }
        
                                    $tpt_vars['_temp']['rule_data'] = $rule;
                                    if(!$rule['is_ajax']) {
                                        //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                        if(isDev('includewrappers')) {
                                            $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                            $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                            extract($fvars);
                                        } else {
                                            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                        }
                                    } else {
                                        ob_start();
                                        //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                        if(isDev('includewrappers')) {
                                            $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                            $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                            extract($fvars);
                                        } else {
                                            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                        }
                                        $tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                        ob_end_clean();
                                        $tpt_vars['environment']['isAjax'] = true;
                                    }
                                }
                            }
                        } else if($rule['url_match_type'] == 'rsegments') {
                            //tpt_dump($rule['url_preg_pattern']);
                            foreach($tpt_vars['url']['bpath'] as $segment) {
                                preg_match($rule['url_preg_pattern'], $segment, $mtch);
                                if(!empty($mtch)) {
                                    //die('a');
                                    $matched = true;
        
        
                                    if(!$rule['sustain_url']) {
                                        $af_byreg = $rule['url_preg_pattern'];
                                        $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                        $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                    }
        
                                    $tpt_vars['_temp']['rule_data'] = $rule;
        
                                    if(!$rule['is_ajax']) {
                                        //tpt_dump($rule);
                                        //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                        if(isDev('includewrappers')) {
                                            $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                            $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                            extract($fvars);
                                        } else {
                                            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                        }
                                    } else {
                                        $tpt_vars['_temp']['rule_data'] = $rule;
                                        ob_start();
                                        //tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, get_defined_vars());
                                        if(isDev('includewrappers')) {
                                            $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                            $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                            extract($fvars);
                                        } else {
                                            include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                        }
                                        $tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                        ob_end_clean();
                                        $tpt_vars['environment']['isAjax'] = true;
                                    }
        
        
                                }
                            }
                        }
        
                        if($matched) {
                        }
                    break;
        
                    case 'passthrough' : // call the page general logic and content
                    default :
                        //die();
                        $mtch = false;
                        //var_dump($rule['url_preg_pattern']);echo '<br />';//die();
                        //var_dump($tpt_vars['url']['upath']);die();
                        //var_dump($tpt_vars['logic']['main_include_file']);die();
                        if($rule['url_match_type'] == 'urpath') {
                            //var_dump($tpt_vars['url']['upath']);die();
                            preg_match($rule['url_preg_pattern'], $tpt_vars['url']['upath'], $mtch);
                        } else if($rule['url_match_type'] == 'rpath') {
                            preg_match($rule['url_preg_pattern'], $tpt_vars['url']['path'], $mtch);
                        } else if($rule['url_match_type'] == 'path') {
                            $mtch = (strstr($tpt_vars['url']['path'], $rule['url_preg_pattern']) !== false);
                        } else if($rule['url_match_type'] == 'segments') {
                            foreach($tpt_vars['url']['bpath'] as $segment) {
                                if(strstr($segment, $rule['url_preg_pattern']) !== false) {
                                    $mtch = true;
                                    break;
                                }
                            }
                        } else if($rule['url_match_type'] == 'rsegments') {
                            foreach($tpt_vars['url']['bpath'] as $segment) {
                                preg_match($rule['url_preg_pattern'], $segment, $mtch);
                                if(!empty($mtch))
                                    break;
                            }
                        }
        
                        //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
                        //if($rule['is_404']) {
                        //    var_dump($tpt_vars['url']);//die();
                        //    var_dump($rule);//die();
                        //    var_dump($mtch);die();
                        //}
                        //}
                        if(!empty($mtch)) {
                            //tpt_dump($rule, true);
                            $status = true;
                            $tpt_vars['environment']['page_rule'] = $rule;
        
                            if(!$rule['sustain_url'] && in_array($rule['url_match_type'], array('urpath', 'rpath', 'rsegments'))) {
                                $af_byreg = $rule['url_preg_pattern'];
                                //var_dump($af_byreg);
                                //var_dump($tpt_vars['url']['bpath']);
                                //die();
                                $tpt_vars['url']['bpath'] = array_filter($tpt_vars['url']['bpath'], 'tpt_af_byreg'); // drop used segment
                                $tpt_vars['url']['path'] = implode('/', $tpt_vars['url']['bpath']); // regenerate $tpt_vars['url']['path']
                                //var_dump($tpt_vars['url']['bpath']);
                                //die();
                                //die('asdasdasdas');
                            }
        
                            if(!$rule['is_ajax']) {
                                $tpt_vars['environment']['force404'] = $rule['is_404'];
                                $tpt_vars['environment']['force414'] = $rule['is_414'];
                                $tpt_vars['logic']['main_include_file'] = $rule['include_file'];
                                if(in_array($tpt_vars['user']['client_ip'], $tpt_vars['config']['urlrule_page_dev_file_ips']) && !empty($rule['dev_include_file'])) {
                                    $tpt_vars['logic']['main_include_file'] = $rule['dev_include_file'];
                                } else {
                                    $tpt_vars['logic']['main_include_file'] = $rule['include_file'];
                                }
                                $tpt_vars['template_data']['meta'][] = $rule['html_meta_tags'];
                                $tpt_vars['template']['title'] = $rule['html_title'];
                                if(!empty($rule['html_head_content']))
                                    $tpt_vars['template_data']['head'][] = $rule['html_head_content'];
                            } else {
                                if($tpt_vars['environment']['force404']) {
                                    die('404');
                                } else if ($tpt_vars['environment']['force414']) {
									die('414');
								}
        
                                $tpt_vars['_temp']['rule_data'] = $rule;
                                //ob_start();
        
                                if(isDev('includewrappers')) {
                                    $evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
                                    $fvars = tpt_functions::f_include_urlrule_ajax_file($tpt_vars, $rule, $evars);
                                    extract($fvars);
                                } else {
                                    include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'urlproc'.DIRECTORY_SEPARATOR.$rule['include_file']);
                                }
                                //$tpt_vars['environment']['ajax_response'] = ob_get_contents();
                                //ob_end_clean();
                                $tpt_vars['environment']['isAjax'] = true;
                            }
        
        
                            if(!empty($rule['left_bar'])) {
                                $tpt_vars['template_data']['hasLeftBar'] = true;
                            }
							//tpt_dump($rule);
							if(!empty($rule['social_bar'])) {
								$tpt_vars['template_data']['hasSocialBar'] = true;
							}
                            $break = true;
                        }
                    break;
                }
            }
        }
    }

}
