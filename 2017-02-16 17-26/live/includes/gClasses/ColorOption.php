<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_ColorOption extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
        
	//die($options['texture']);
	//var_dump($options['texture']);die();
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity center ';
	$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'color-option-mask.png ';
	//$IMCommand .= '	-alpha Off ';
	$IMCommand .= '	-compose CopyOpacity ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'color-option-shade.png ';
	$IMCommand .= '	-composite ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	png:- ';
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	//$IMCommand .= '	-background transparent ';
	$IMCommand .= '	png:- ';
	$IMCommand .= '	-gravity center ';
	$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'color-option-mask.png ';
	//$IMCommand .= '	-alpha Off ';
	$IMCommand .= '	-compose CopyOpacity ';
	$IMCommand .= '	-composite ';
	$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'color-option-shades.png ';
	$IMCommand .= '	-compose Over';
	$IMCommand .= '	-composite ';
	//$IMCommand .= '	-trim ';
	$IMCommand .= '	png:- ';
	
	$this->exec($vars, $steps, 'ColorOption', $IMCommand, 3, $options['texture']);
	//var_dump($steps['errors']['TextMask']);die();
	$cfile = $options['cfile'];
	//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218')
	if(!empty($cfile))
	    file_put_contents($cfile, $steps['ColorOption']);
	return $steps['ColorOption'];        
    }
}