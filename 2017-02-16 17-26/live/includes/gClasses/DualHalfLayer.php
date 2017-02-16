<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_DualHalfLayer extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	//die('asdasdasdasas');

		//die('asdasdasdas');
	    $types_module = getModule($vars, "BandType");
	    //var_dump($options['pgType']);die();
	    //var_dump($types_module->moduleData['id'][$options['pgType']]['preview_folder']);die();
            $pgDir = $types_module->moduleData['id'][$options['bandType']]['preview_folder'];
	    
	    
	    /*
            $overlay = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'dual-layer-inner-overlay-black-tile.png');
	    if($options['bandColor'] == 'rgb(0,0,0)')
		$overlay = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'dual-layer-inner-overlay-white-tile.png');
	    */
	    
	    $overlay = '';
	    
	    
	    
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
	    
	    
	    
	    
	    if(true || !empty($options['invert_dual'])) {
            //$mask = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.'slap'.DIRECTORY_SEPARATOR.'dual-layer-mask.png');
            //$shade = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'dual-layer-shade.png');
	    
	    //var_dump(reset($options['invColor']));die();
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size 3x40 ';
	    $IMCommand .= '	xc:\''.$options['bandColor'].'\' ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-composite ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-gravity center ';
	    //$IMCommand .= '	-alpha Off ';
	    //$IMCommand .= '	-compose CopyOpacity ';
	    //$IMCommand .= '	-composite ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	-compose Over ';
	    //$IMCommand .= '	-composite ';
	    //$IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'DualInner', $IMCommand, 2);
	    //if($_SERVER['REMOTE_ADDR'])
	    //var_dump($options);die();
	    //tpt_dump($IMCommand, true);
	    //var_dump($steps['errors']);die();
	    //return $steps['SomeStuff'];
	    $overlay = $steps['DualInner'];
	    }
	    
	    $cfile = $options['cfile'];
	    //var_dump($cfile);die();
	    if(!empty($cfile)) {
		file_put_contents($cfile, $overlay);
	    }
	
	//die($overlay);
        return $overlay;
        
    }
    
}