<?php
//die('asfasgdgsda');
defined('TPT_INIT') or die('access denied');

//tpt_dump($tpt_vars['data']['tpt_ajax_calls']['task'][$task]['include_file'], true);
//tpt_dump($tpt_vars['environment']['isAdministration'], true);

$tpt_vars['environment']['isTask'] = true;
$task = @$_GET['tpt_task'];

//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
 //   var_dump('tas',$task);
//    die('???');
//}    



$ajaxFailed = false;
if(isset($_GET['tpt_task'])) {
    $task = $_GET['tpt_task'];
    
//	if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
//	    var_dump('tas2',$task);
//	    die('wwwww');
//	}    
    
    //tpt_dump(tpt_ajax::$calls, true);
    if(empty($_GET['tpt_task'])) {
        $tpt_vars['environment']['ajax_result']['messages'][] = array('No action specified!',  'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('You can try refreshing the page to see if that will fix the problem.',  'tpt_tip');
            //'You can try refreshing the page to see if that will fix the problem.'=>array('class_sfx'=>' tpt_tip'),
        //);
        $ajaxFailed = true;
    } else if(empty(tpt_ajax::$calls[$_GET['tpt_task']])) {
        // invalid action
		//tpt_dump($tpt_vars['user']['data'], true);
        $tpt_vars['environment']['ajax_result']['messages'][] = array('No action specified!',  'error');
        $tpt_vars['environment']['ajax_result']['messages'][] = array('You can try refreshing the page to see if that will fix the problem.',  'tpt_tip');
        $ajaxFailed = true;
    } else if(tpt_ajax::$calls[$_GET['tpt_task']]['access_level']>$tpt_vars['user']['data']['access_level']) {
		$tpt_vars['environment']['ajax_result']['messages'][] = array('No action specified!',  'error');
		$tpt_vars['environment']['ajax_result']['messages'][] = array('You can try refreshing the page to see if that will fix the problem.',  'tpt_tip');
		$ajaxFailed = true;
		//tpt_dump($tpt_vars['user']['data'], true);
	}

    
    if(!$ajaxFailed) {



        $taskElms = explode('.', $_GET['tpt_task']);
        $taskElms = $task[count($task)-1];

		//	if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
			//	var_dump(TPT_RESOURCE_DIR,'fff',is_file(tpt_ajax::$calls[$task]['include_file']),is_file($incl_file_=TPT_RESOURCE_DIR.'/'.tpt_ajax::$calls[$task]['include_file']));
			//	var_dump($incl_file_);
				//var_dump(tpt_ajax::$calls[$task]);
			
			//	die('zz');
		//	}
		
		$incl_file = is_file(tpt_ajax::$calls[$task]['include_file']) ? tpt_ajax::$calls[$task]['include_file'] : false;
		!$incl_file && is_file($incl_file_=TPT_RESOURCE_DIR.'/'.tpt_ajax::$calls[$task]['include_file']) ? $incl_file=$incl_file_ : false;

        
        if(!empty(tpt_ajax::$calls[$task]['include_file']) && $incl_file) {
//        if(!empty(tpt_ajax::$calls[$task]['include_file']) && is_file(tpt_ajax::$calls[$task]['include_file'])) {

			



            //tpt_dump(tpt_ajax::$calls[$task]['include_file'], true);
        //    include_once(tpt_ajax::$calls[$task]['include_file']);
            include_once($incl_file); // fix
            
            //tpt_dump($task);
            //tpt_dump(tpt_ajax::$calls[$task]);
            //tpt_dump('asdasdasdasds', true);
        }

		//tpt_dump($tpt_vars['data']['tpt_ajax_calls']['task'], true);
        $tpt_vars['environment']['ajax_call']['task'] = isset($tpt_vars['data']['tpt_ajax_calls']['task'][$task]) ?
            $tpt_vars['data']['tpt_ajax_calls']['task'][$task] : '';
    }
}

//tpt_dump($tpt_vars['environment']['ajax_result']['update_elements']['rcp'], true);