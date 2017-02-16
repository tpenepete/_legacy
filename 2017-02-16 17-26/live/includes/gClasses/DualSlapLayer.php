<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_DualSlapLayer extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	//die('asdasdasdasas');

		//die('asdasdasdas');
	    $data_module = getModule($vars, "BandData");
	    //var_dump($options);die();
	    //var_dump($options['pgType']);die();
	    //var_dump($types_module->moduleData['id'][$options['pgType']]['preview_folder']);die();
        if(isset($options['bandStyle'])) {
            $pgDir = $data_module->typeStyle[$options['bandType']][$options['bandStyle']]['preview_folder'];
        }
	    
            $overlay = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'dual-layer-inner-overlay-black.png');
	    if($options['bandColor'] == 'rgb(0,0,0)')
		$overlay = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'dual-layer-inner-overlay-white.png');
	    
	    
	    
	    
	    //$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '	-size 578x62 ';
	    //$IMCommand .= '	xc:\''.$options['bandColor'].'\' ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-composite ';
	    //$IMCommand .= '	png:- ';
	    //$this->exec($vars, $steps, 'SomeStuff', $IMCommand, 3, $shade);
	    //if($_SERVER['REMOTE_ADDR'])
	    //var_dump($steps['errors']);die();
	    //return $steps['SomeStuff'];
	    
	    
	    
	    //tpt_dump($options['invert_dual']);
	    //tpt_dump($options['notched'], true);
	    if(!empty($options['invert_dual'])) {
            $mask = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'slap'.DIRECTORY_SEPARATOR.'dual-layer-mask.png');
            //$shade = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'dual-layer-shade.png');
		
	    //var_dump($options['invColor']);die();
	    //echo $options['invColor'];
	    //header('Content-type: image/png');
	    //return $options['invColor'];;
	    //die();
		
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size 578x62 ';
	    //$IMCommand .= '	xc:\''.reset($options['invColor']).'\' ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-composite ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	-gravity center ';
	    //$IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-compose Over ';
	    //$IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'DualSlapInner', $IMCommand, 3, $options['bandColor'].$mask/*.$shade*/);
	    //if($_SERVER['REMOTE_ADDR'])
	    //var_dump($options);die();
	    //var_dump($IMCommand);die();
	    //var_dump($steps['errors']);die();
	    //return $steps['SomeStuff'];
	    $overlay = $steps['DualSlapInner'];
	    }

		/*
	    $cfile = $options['cfile'];
	    //var_dump($cfile);die();
	    if(!empty($cfile)) {
		file_put_contents($cfile, $overlay);
	    }
		*/
	

        return $overlay;
        
    }
    
}