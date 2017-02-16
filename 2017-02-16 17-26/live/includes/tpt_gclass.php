<?php
defined('TPT_INIT') or die('access denied');

class tpt_gClass {
    
    function convert(&$vars, &$steps, $stepId, $command, $pipenum=2, $input='') {
        
        //$command = escapeshellcmd($command);
        $command = preg_replace('#\\\\\((.*)\\\\\)#s', '($1)', $command);
        $steps['commands'][$stepId] = $command;
        
        $descriptorspec = array();
        switch($pipenum) {
            case 1 :
                $descriptorspec = array(
                        1 => array("pipe", "w")
                );
                break;
            case 3 :
                $descriptorspec = array(
                        0 => array("pipe", "r"),
                        1 => array("pipe", "w"),
                        2 => array("pipe", "w")
                );
                break;
            default :
                $descriptorspec = array(
                        1 => array("pipe", "w"),
                        2 => array("pipe", "w")
                );
                break;
        }
        
	$process = proc_open($command, $descriptorspec, $pipes);
        $add = '';
        $error = '';
        
        
	if (is_resource($process)) {
            if($pipenum > 2) {
                fwrite($pipes[0], $input);
                fclose($pipes[0]);
            }
            
	    while (!feof($pipes[1])) {
	        $add .= fgets($pipes[1]);
	    }
	     
	    fclose($pipes[1]);
            
            if($pipenum > 1) {
                while (!feof($pipes[2])) {
                    $error .= fgets($pipes[2]);
                }
                
                fclose($pipes[2]);
            }
	     
	    $return_value = proc_close($process);
	}
        
        $steps[$stepId] = $add;
        if($pipenum > 1) {
            $steps['errors'][$stepId] = $error;
        }
        
        return $return_value;
    }
    
}