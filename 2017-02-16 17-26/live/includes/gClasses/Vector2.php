<?php
defined('TPT_INIT') or die('access denied');

class tpt_gclass_Vector extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	
	
	//$path = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin:/usr/lib64"; ';
	
	//$IMCommand = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin";';
	//$IMCommand = 'export PATH="/usr/local/bin"; echo $PATH';
	//$this->exec($vars, $steps, 'path', $IMCommand, 2);
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -list delegate';
	//$this->exec($vars, $steps, 'delegates', $IMCommand, 2);
	//var_dump(nl2br($steps['delegates']));die();
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -list configure';
	//$this->exec($vars, $steps, 'configure', $IMCommand, 2);
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' -version';
	//$this->exec($vars, $steps, 'version', $IMCommand, 2);
	$IMCommand = dirname(__FILE__).DIRECTORY_SEPARATOR.'ss';
	//$this->exec($vars, $steps, 'shelltest', $IMCommand, 2);

	@unlink(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'potrace.svg');
	
	$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	$IMCommand .= '			-size x200 ';
	//$IMCommand .= '			-extent '.$options['X'].'x'.$options['Y'].' ';//'.$options['X']*$options['Y'].' ';
	//$IMCommand .= '			-background transparent ';
	$IMCommand .= '			-fill black ';
	$IMCommand .= '			-stroke none ';
	$IMCommand .= '			-gravity Center ';

	//$IMCommand .= '			-interline-spacing 0 ';
	$IMCommand .= '			-pointsize 14 ';
	$IMCommand .= '			-font '.escapeshellarg(FONTS_PATH.DIRECTORY_SEPARATOR.$options['font']).' ';
	$IMCommand .= '					 label:'.escapeshellarg($options['text']).' ';
	$IMCommand .= '			-trim ';
	//$IMCommand .= '			miff:- ';
	//$IMCommand .= '			'.$options['format'].':- ';
	$IMCommand .= '	'.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.pnm ';
	//var_dump(shell_exec($IMCommand));die();
	$this->exec($vars, $steps, 'label', $IMCommand, 2);
	//var_dump($steps['errors']['label']);die();
	//var_dump($steps['label']);die();
	//header('Content-type: image/svg+xml');
	//return $steps['label'];
	
	
	//$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	//$IMCommand .= '			autotrace:\''.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.png\' ';
	//$IMCommand .= '	bahur.png ';
	//$this->exec($vars, $steps, 'vector', $IMCommand, 2);
	//unlink(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.png');
	//var_dump($steps['errors']['vector']);die();
	////header('Content-type: image/svg+xml');
	////return $steps['vector'];
	
	//$IMCommand = '/usr/bin/autotrace ';
	//$IMCommand .= '			--input-format=png ';
	//$IMCommand .= '			--dpi=1024 ';
	//$IMCommand .= '			--color-count=16 ';
	//$IMCommand .= '			--despeckle-level=12 ';
	//$IMCommand .= '			--despeckle-tightness=1 ';
	//$IMCommand .= '			--corner-always-threshold=60 ';
	//$IMCommand .= '			--line-threshold=0.01 ';
	//$IMCommand .= '			--width-weight-factor=0.01 ';
	//$IMCommand .= '			--line-reversion-threshold=0.01 ';
	//$IMCommand .= '			--preserve-width ';
	//$IMCommand .= '			--remove-adjacent-corners ';
	//$IMCommand .= '			--output-file='.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.svg';
	//$IMCommand .= '			'.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.png ';
	//$this->exec($vars, $steps, 'vector', $IMCommand, 2);
	//@unlink(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.png');
	////var_dump($steps['errors']['vector']);die();
	////header('Content-type: image/svg+xml');
	////return $steps['vector'];
	//return;
	
	$IMCommand = '/usr/bin/potrace ';
	$IMCommand .= '			--svg ';
	//$IMCommand .= '			--input-format=png ';
	//$IMCommand .= '			--resolution 72 ';
	$IMCommand .= '			--scale 1 ';
	$IMCommand .= '			--unit 2 ';
	$IMCommand .= '			--blacklevel 0.48 ';
	//$IMCommand .= '			--turnpolicy right ';
	$IMCommand .= '			--turdsize 0 ';
	$IMCommand .= '			--alphamax 0.6 ';
	//$IMCommand .= '			--fillcolor #ffffff ';
	$IMCommand .= '			--longcurve ';
	//$IMCommand .= '			--opttolerance 0.2 ';
	$IMCommand .= '			--width '.$options['X'].' ';
	//$IMCommand .= '			--height '.$options['Y'].' ';
	//$IMCommand .= '			--dpi=1024 ';
	//$IMCommand .= '			--color-count=16 ';
	//$IMCommand .= '			--despeckle-level=12 ';
	//$IMCommand .= '			--despeckle-tightness=1 ';
	//$IMCommand .= '			--corner-always-threshold=60 ';
	//$IMCommand .= '			--line-threshold=0.01 ';
	//$IMCommand .= '			--width-weight-factor=0.01 ';
	//$IMCommand .= '			--line-reversion-threshold=0.01 ';
	//$IMCommand .= '			--preserve-width ';
	//$IMCommand .= '			--remove-adjacent-corners ';
	$IMCommand .= '			--output '.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'potrace.svg';
	$IMCommand .= '			'.TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.pnm ';
	$this->exec($vars, $steps, 'vector', $IMCommand, 2);
	@unlink(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'test.pnm');
	//var_dump($steps['errors']['vector']);die();
	$steps['vector'] = file_get_contents(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'potrace.svg');
	header('Content-type: image/svg+xml');
	return $steps['vector'];
	//return;
	
	
	//$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	////$IMCommand .= '			'.$options['format'].':- ';
	//$IMCommand .= '			autotrace:- ';
	//$IMCommand .= '	svg:- ';
	//$this->exec($vars, $steps, 'vector', $IMCommand, 3, $steps['label']);
	//var_dump($steps['errors']['vector']);die();
	//header('Content-type: image/svg+xml');
	//return $steps['vector'];

	/*
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
	$IMCommand .= '			-background white ';
	$IMCommand .= '			-extent '.$options['X'].'x'.$options['Y'].' ';//'.$options['X']*$options['Y'].' ';
	$IMCommand .= '			-gravity center ';
	$IMCommand .= '	png:- ';
	//$IMCommand .= '			-resize '.$options['X'].'x'.$options['Y'].'^ ';//'.$options['X']*$options['Y'].'@ ';

	//$IMCommand .= '			'.$options['format'].':- ';
	$IMCommand .= '	jpg:- ';
	//$IMCommand .= '	png:- ';
	$this->exec($vars, $steps, 'Text2', $IMCommand, 3, $steps['label']);
	
	
	if(!empty($steps['Text2']))
	    file_put_contents($filename, $steps['Text2']);
	


	
	
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
        
        
        
        
        //return $steps['shrink'];
        return $steps['Text2'];
    }
}