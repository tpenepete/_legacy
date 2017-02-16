<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Solid extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
        //var_dump($options);die();
        //var_dump(reset($options['solidColor']));die();
	    
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	$IMCommand .= '	xc:\''.reset($options['solidColor']).'\' ';
	$IMCommand .= '	png:- ';
	
	$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'Solid', $IMCommand, 2);
	//var_dump($steps['errors']['Swirl']);die();
	//var_dump($options['swirlColor']);die();
	//header('Content-type: image/png');
	//return $steps['Swirl'];
	    
	    
	if(!empty($options['glitter'])) {
	    $flt = intval($options['glitter'], 10);
	    $gfile = 'glitter.png';
	    if($flt == 2) {
		$gfile = 'mc-glitter.png';
	    }
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	'.escapeshellarg(str_replace('\\', '\\\\', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'glitter'.DIRECTORY_SEPARATOR.$gfile)).' ';
	    $IMCommand .= '	-tile ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Solid', $IMCommand, 3, $steps['Solid']);
	    //$BandBG[] = $steps['Glitter'];
	    //return $steps['BandNoText'];
	    //var_dump($steps);die();
	}
	
	if(!empty($options['powdercoat'])) {
	    $flt = intval($options['powdercoat'], 10);
	    $gfile = 'powdercoat.png';
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	'.escapeshellarg(str_replace('\\', '\\\\', TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'powdercoat'.DIRECTORY_SEPARATOR.$gfile)).' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Solid', $IMCommand, 3, $steps['Solid']);
	    //$BandBG[] = $steps['Glitter'];
	    //return $steps['BandNoText'];
	    //var_dump($steps);die();
	}
	
	$cfile = !empty($options['cfile'])?$options['cfile']:'';
	if(!empty($cfile))
	    file_put_contents($cfile, $steps['Solid']);
	//header('Content-type: image/png');
        //die($steps['Solid']);
        return $steps['Solid'];
        
    }
    
}