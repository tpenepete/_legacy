<?php
defined('TPT_INIT') or die('access denied');

class tpt_gclass_Simple extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	
	/*
        $format = 'png:-';
        switch($options['format']) {
            case 'jpg' :
                $format = 'jpg:-';
                break;
            default:
                $format = 'png:-';
                break;
        }
	*/
	//die();
	//if(!is_file($filename)) {
	    //$path = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin:/usr/lib64"; ';
	    
	    //$IMCommand = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin";';
	    //$IMCommand = 'export PATH="/usr/local/bin"; echo $PATH';
	    //$this->exec($vars, $steps, 'path', $IMCommand, 2);
	    
	    //$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -list delegate';
	    //$this->exec($vars, $steps, 'delegates', $IMCommand, 2);
	    //$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -list configure';
	    //$this->exec($vars, $steps, 'configure', $IMCommand, 2);
	    //$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -version';
	    //$this->exec($vars, $steps, 'version', $IMCommand, 2);
	    //$IMCommand = dirname(__FILE__).DIRECTORY_SEPARATOR.'ss';
	    //$this->exec($vars, $steps, 'shelltest', $IMCommand, 2);
    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '			-size '.$options['X'].'x'.$options['Y'].' ';
	    //$IMCommand .= '			-extent '.$options['X'].'x'.$options['Y'].' ';//'.$options['X']*$options['Y'].' ';
	    $IMCommand .= '			-background transparent ';
	    $IMCommand .= '			-fill black ';
	    $IMCommand .= '			-stroke none ';
	    $IMCommand .= '			-gravity Center ';

	    //$IMCommand .= '			-interline-spacing 0 ';
	    //$IMCommand .= '			-pointsize 14 ';
	    $IMCommand .= '			-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '					 label:'.$options['text'].' ';
	    $IMCommand .= '			-trim ';
	    //$IMCommand .= '			miff:- ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '	png:- ';
	    //var_dump(shell_exec($IMCommand));die();
	    $this->exec($vars, $steps, 'label', $IMCommand, 2);
	    //header('Content-type: image/png');
	    //return $steps['label'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$options['vpad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'label', $IMCommand, 3, $steps['label']);
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.$options['vpad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'label', $IMCommand, 3, $steps['label']);
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity west ';
	    $IMCommand .= '	-splice '.$options['hpad'].'x0 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'label', $IMCommand, 3, $steps['label']);
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity east ';
	    $IMCommand .= '	-splice '.$options['hpad'].'x0 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'label', $IMCommand, 3, $steps['label']);
	    $cfile = $options['cfile'];
	    //if($_SERVER['REMOTE_ADDR'] == '109.160.0.218')
	    /*
	    if(!empty($cfile))
		if(!empty($steps['label']))
		    file_put_contents($cfile, $steps['label']);
	    //header('Content-type: image/'.$options['format']);
	    */
	    if(!empty($options['cfile']) && !(isDump() && !empty($vars['config']['dev']['debugpreviews_purge'])) && (empty($_GET['debug_purge']))) {
		file_put_contents($options['cfile'], $steps['label']);
	    }
	    return $steps['label'];
	    
	    /*$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '			'.$format.' ';
	    //$IMCommand .= '			-resize '.$options['X'].'x'.$options['Y'].'^ ';//'.$options['X']*$options['Y'].'@ ';
	    $IMCommand .= '			-resize '.$options['X'].'x'.$options['Y'].' ';//'.$options['X']*$options['Y'].'@ ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '	png:- ';
	    //$this->exec($vars, $steps, 'enlarge_fill', $IMCommand, 3, $steps['label']);

	    //var_dump($steps['enlarge_fill']);die();
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-format "%h" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'TextHeight', $IMCommand, 2, $steps['label']);
	    //return $steps['TextHeight'];
	    $botPadding = max(0, ceil(($options['Y'] - $steps['TextHeight'])/2));
	    $topPadding = max(0, ($options['Y'] - $botPadding));
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$topPadding.' ';
	    $IMCommand .= '	png:- ';

	    $this->exec($vars, $steps, 'Text', $IMCommand, 3, $steps['label']);
	    //var_dump($steps['Text']);//die();
	    //return $steps['TextTopPad'];

	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.$botPadding.' ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand = BIN_PATH.'identify ';
	    //$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Text2', $IMCommand, 3, $steps['Text']);
	    //var_dump($steps['Text2']);die();
	    //var_dump($steps['errors']['Text2']);die();
	    //return $steps['TextBotPad'];
	    */
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '			-background transparent ';
	    $IMCommand .= '			-extent '.$options['X'].'x'.$options['Y'].' ';//'.$options['X']*$options['Y'].' ';
	    $IMCommand .= '			-gravity center ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '			-resize '.$options['X'].'x'.$options['Y'].'^ ';//'.$options['X']*$options['Y'].'@ ';

	    //$IMCommand .= '			'.$options['format'].':- ';
	    //$IMCommand .= '	jpg:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Text2', $IMCommand, 3, $steps['label']);
	    
	    
	    
		//file_put_contents($filename, $steps['Text2']);
	    


	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '			'.$format.' ';
	    $IMCommand .= '			-resize '.$options['X'].'x'.$options['Y'].'\> ';//'.$options['X']*$options['Y'].'@ ';
	    //$IMCommand .= '			'.$options['format'].':- ';
	    $IMCommand .= '			'.$format.' ';
	    //$this->exec($vars, $steps, 'shrink', $IMCommand, 3, $steps['enlarge_fill']);
	    //var_dump($steps);die();
	    
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '			-list configure ';
	    //$IMCommand .= '			logo: logo.png ';
	    //$IMCommand .= '			-version ';
	    $IMCommand = 'echo $PATH';
	    //$this->exec($vars, $steps, 'test', $IMCommand, 2);
	//}
	/*
	else {
	    $steps['Text2'] = file_get_contents($filename);
	}
	*/
        
        
        //var_dump($steps['Text2']);
        
        //return $steps['shrink'];
	$cfile = $options['cfile'];
	//if($_SERVER['REMOTE_ADDR'] == '109.160.0.218')
	if(!empty($cfile))
	    if(!empty($steps['Text2']))
		file_put_contents($cfile, $steps['Text2']);
	//header('Content-type: image/'.$options['format']);
        return $steps['Text2'];
    }
}