<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Flat extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	
	$which = '';
	//return reset($options['left']);
	//var_dump(reset($options['left']));die();
	//$return = $options[$which];
	//$return = reset($options['left']);
	//return reset($options['left']);
		//tpt_dump($options, true);
	
	$both = array();
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.$options['pg_x'].'x'.$options['pg_yp'].' ';
	//$IMCommand .= '	-background black ';
	$IMCommand .= '	xc:\'transparent\' ';
	//$IMCommand .= '	xc:transparent ';\
	$lInput = '';
	$i = 0;
	$gravities = array();
	$gravities[] = 'North';
	$gravities[] = 'South';
	foreach($options['left'] as $elm) {
	//$IMCommand .= '	-gravity North ';
	$IMCommand .= '	-gravity '.$gravities[$i].' ';
	$IMCommand .= '	png:- ';
	$lInput .= $elm;
	$IMCommand .= '	-composite ';
	//$IMCommand .= '	-gravity East ';
	//$IMCommand .= '	png:- ';
	//$IMCommand .= '	-composite ';
	$i++;
	}
	//$IMCommand .= '	+append ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'left', $IMCommand, 3, $lInput);
	$both[] = $steps['left'];
	//die($options['pg_x']);
	//header('Content-type: image/png');
	//return reset($both);
	//return reset($options['left']);
	//if(isDev()) {
	//return $steps['left'];
	//}
	
	//var_dump($options['right']);die();
	if(!empty($options['right'])) {
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.$options['pg_x'].'x'.$options['pg_yp'].' ';
	//$IMCommand .= '	-background black ';
	$IMCommand .= '	xc:\'transparent\' ';
	//$IMCommand .= '	xc:transparent ';\
	$lInput = '';
	$i = 0;
	$gravities = array();
	$gravities[] = 'North';
	$gravities[] = 'South';
	foreach($options['right'] as $elm) {
	//$IMCommand .= '	-gravity Center ';
	$IMCommand .= '	-gravity '.$gravities[$i].' ';
	$IMCommand .= '	png:- ';
	$lInput .= $elm;
	$IMCommand .= '	-composite ';
	//$IMCommand .= '	-gravity East ';
	//$IMCommand .= '	png:- ';
	//$IMCommand .= '	-composite ';
	$i++;
	}
	//$IMCommand .= '	+append ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'right', $IMCommand, 3, $lInput);
	$both[] = $steps['right'];
	//return $steps['right'];
	}
	
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.$options['pg_x'].'x'.$options['pg_yp'].' ';
	$IMCommand .= '	-background transparent ';
	//$IMCommand .= '	xc:\'transparent\' ';
	//$IMCommand .= '	xc:transparent ';\
	$lInput = '';
	$i = 0;
	$gravities = array();
	$gravities[] = 'West';
	$gravities[] = 'East';
	foreach($both as $elm) {
	$IMCommand .= '	-gravity '.$gravities[$i].' ';
	$IMCommand .= '	png:- ';
	$lInput .= $elm;
	$i++;
	}
	//$IMCommand .= '	-composite ';
	//$IMCommand .= '	-gravity East ';
	//$IMCommand .= '	png:- ';
	//$IMCommand .= '	-composite ';
	$IMCommand .= '	+append ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'message', $IMCommand, 3, $lInput);
	//header('Content-type: image/png');
	//return reset($both);
	//return $steps['message'];
	
	
	/*
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.($options['pg_x']+$options['canvasPaddingLeft']).'x'.$options['pg_yp'].' ';
	$IMCommand .= '	xc:\'transparent\' ';
	//$IMCommand .= '	xc:\'transparent\' ';
	//$IMCommand .= '	xc:transparent ';\
	$IMCommand .= '	-gravity west ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-composite ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'message', $IMCommand, 3, $lInput);
	return $steps['message'];
	*/
    
	//var_dump($options['canvasPaddingLeft']);die();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	-gravity west ';
	//$IMCommand .= '	+repage ';
	$IMCommand .= '	-background transparent ';
	$IMCommand .= '	-splice '.$options['canvasPaddingLeft'].'x0 ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'message', $IMCommand, 3, $steps['message']);
	//return $steps['message'];
	
	
	if(($options['style'] == 7)) {
	if(($options['type'] == 5)) {
	    //header('Content-type: image/png');
	    //echo $options['dualoverlay'];
	    //die();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity Center ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['bg'].$options['dualoverlay']);
	$options['bg'] = $steps['bgandover'];
	} else if(!empty($options['notched'])) {
	    //return $options['bg'];
	    //return $options['dualoverlay'];
	/*
	$IMCommand = BIN_PATH.'composite ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity West ';
	$IMCommand .= '	-geometry +0+5 ';
	//$IMCommand .= '	-tile-offset +0-10 ';
	$IMCommand .= '	-tile ';
	//$IMCommand .= '	+repage ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['dualoverlay'].$options['bg']);
	*/
	
	/*
	$IMCommand = BIN_PATH.'composite ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity West ';
	$IMCommand .= '	-geometry +0+5 ';
	//$IMCommand .= '	-tile-offset +0-10 ';
	$IMCommand .= '	-tile ';
	//$IMCommand .= '	+repage ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['dualoverlay'].$options['bg']);
	$options['bg'] = $steps['bgandover'];

	*/
	//header('Content-type: image/png');
	//die($options);
	//die($options['bg'].$options['dualoverlay']);
	//tpt_dump($options['del_height'],true);
	//tpt_dump($options['bg'].$options['dualoverlay'],true);
		//return $options['bg'];
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	\\( -size 800x'.$options['del_height'].' tile:png:- \\) ';
	$IMCommand .= '	-gravity Center ';
	$IMCommand .= '	-compose Over ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['bg'].$options['dualoverlay']);
	$options['bg'] = $steps['bgandover'];
	//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218') {
	    //tpt_dump($IMCommand, true);
	    //tpt_dump($steps['errors'],true);
	    //tpt_dump($steps['errors'],true);
	//tpt_dump($options,true);
	//return $options['dualoverlay'];
	//return $options['bg'];
	//return $options['bg'];
	//}
	}
	} else if((($options['style'] == 6) && ($options['type'] == 5) && !empty($options['cut_away'])) || ($options['style'] == 8)) {
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity Center ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-geometry +0+2 ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['bg'].$options['dualoverlay']);
	$options['bg'] = $steps['bgandover'];
	} else if(($options['style'] == 16)){
		//tpt_dump($options['bg'], true);
		//return $options['bg'];
		//return $options['dualoverlay'];
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	\\( -size 800x'.$options['del_height'].' tile:png:- \\) ';
	$IMCommand .= '	-gravity Center ';
	$IMCommand .= '	-compose Over ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'bgandover', $IMCommand, 3, $options['bg'].$options['dualoverlay']);
	$options['bg'] = $steps['bgandover'];
		//tpt_dump($steps, true);
		//tpt_dump($IMCommand, true);
		//return $options['bg'];
	}

	//die($options['pg_fx']);
	//return $options['bg'];
	
	$compose = '';
	//$composite = array();
	//if(!empty($options['dualoverlay'])) {
	//    $composite[] = $options['dualoverlay'];
	//}
	//$composite[] = $steps['message'];
	//$composite[] = $options['outline'];
	//var_dump($options['fullWidth']);die();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.$options['pg_fx'].'x'.$options['pg_fy'].' ';
	$IMCommand .= '	xc:\'transparent\' ';
	$IMCommand .= '	-gravity center ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	-gravity west ';
	$compose .= $steps['message'];
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-composite ';
	$compose .= $options['outline'];
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-composite ';
	//$compose .= $layer;
	$IMCommand .= '	png:- ';
	//$IMCommand .= '	bahur.png ';
	$this->exec($vars, $steps, 'textandbg', $IMCommand, 3, $options['bg'].$compose);
        
	//die();
        //return $return;
	
	$cfile = !empty($options['cfile'])?$options['cfile']:'';
	//tpt_dump($cfile,true);
	if(!empty($cfile)) {
	    file_put_contents($cfile, $steps['textandbg']);
	    //tpt_dump(file_get_contents($cfile),true);
	}
        return $steps['textandbg'];
        
    }
    
}