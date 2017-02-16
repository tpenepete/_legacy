<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Debossed extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
        
	//var_dump($options['swirlColor']);die();
	
        $format = 'jpg:-';
        switch($options['format']) {
            case 'png' :
                $format = 'png:-';
                break;
            default:
                $format = 'jpg:-';
                break;
        }
        
        //$path = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin:/usr/lib64"; ';
        
        //$IMCommand = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin";';
        //$IMCommand = 'export PATH="/usr/local/bin"; echo $PATH';
        //$this->exec($vars, $steps, 'path', $IMCommand, 2);
        echo BIN_PATH;die();
	if(!empty($options['text'])) {
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	-background black ';
	    $IMCommand .= '	-fill white ';
	    $IMCommand .= '	-stroke none ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '			 label:"'.$options['text'].'" ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 2);
	    //return $steps['WhiteTextOnBlackBG'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-fill black ';
	    $IMCommand .= '	-stroke none ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '			 label:"'.$options['text'].'" ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'BlackTextNoBG', $IMCommand, 2);
	    //return $steps['BlackTextNoBG'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'black\' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	 -geometry -0-'.$options['extrude'].' -composite  ';
	    $IMCommand .= '	-trim ';	
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'TextHighlightMask', $IMCommand, 3, $steps['WhiteTextOnBlackBG'].$steps['BlackTextNoBG']);
	    //var_dump($steps['errors']['TextHighlightMask']);die();
	    //return $steps['TextHighlightMask'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'white\' ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	 -geometry -0+'.$options['extrude'].' ';
	    $IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextHighlightFull', $IMCommand, 3, $steps['TextHighlightMask']);
	    //var_dump($steps['errors']['TextHighlightFull']);die();
	    //return $steps['TextHighlightFull'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-channel Alpha ';
	    $IMCommand .= '	-evaluate Divide 3 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextHighlight', $IMCommand, 3, $steps['TextHighlightFull']);
	    //var_dump($steps['errors']['TextHighlight']);die();
	    //return $steps['TextHighlight'];
	    
	    
	    
	    
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'black\' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-trim ';	
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'TextMaskMask', $IMCommand, 3, $steps['WhiteTextOnBlackBG'].$steps['WhiteTextOnBlackBG']);
	    //var_dump($steps['errors']['TextMaskMask']);die();
	    //return $steps['TextMaskMask'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'white\' ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMask', $IMCommand, 3, $steps['TextMaskMask']);
	    //var_dump($steps['errors']['TextMask']);die();
	    //return $steps['TextMask'];
	    
	    
	    
	    
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'black\' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	 -geometry +'.$options['extrude'].'+'.$options['extrude'].' -composite  ';
	    $IMCommand .= '	-trim ';	
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'TextShadowMask', $IMCommand, 3, $steps['WhiteTextOnBlackBG'].$steps['BlackTextNoBG']);
	    //var_dump($steps['errors']['TextShadowMask']);die();
	    //return $steps['TextShadowMask'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:\'black\' ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextShadowFull', $IMCommand, 3, $steps['TextShadowMask']);
	    //var_dump($steps['errors']['TextShadowFull']);die();
	    //return $steps['TextShadowFull'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-channel Alpha ';
	    $IMCommand .= '	-evaluate Divide 2 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextShadow', $IMCommand, 3, $steps['TextShadowFull']);
	    //var_dump($steps['errors']['TextShadow']);die();
	    //return $steps['TextShadow'];
	    
	    
	    
	    
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	xc:transparent ';
	    $IMCommand .= '	-gravity center ';
	    //for($i=1;$i<=$emboss;$i++) {
	    //}
	    //for($i=1;$i<=$emboss;$i++) {
	    //}
	    $IMCommand .= '	png:- -geometry -0+'.$options['extrude'].' -composite ';
	    $IMCommand .= '	png:- -composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	+repage ';
	    $this->exec($vars, $steps, 'TextReliefTrim', $IMCommand, 3, $steps['TextHighlight'].$steps['TextShadow']);
	    //return $steps['TextReliefTrim'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-format "%w" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'TextReliefWidth', $IMCommand, 3, $steps['TextReliefTrim']);
	    //return $steps['TextReliefWidth'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-format "%h" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'TextReliefHeight', $IMCommand, 3, $steps['TextReliefTrim']);
	    //return $steps['TextReliefHeight'];
	    $addPadding = abs(round(($options['Y'] - $steps['TextReliefHeight'] - 6)/2));
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$options['toppad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextReliefTopPad', $IMCommand, 3, $steps['TextReliefTrim']);
	    //return $steps['TextReliefTopPad'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.($options['botpad']+$addPadding).' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextReliefVPad', $IMCommand, 3, $steps['TextReliefTopPad']);
	    //return $steps['TextReliefVPad'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$options['toppad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMaskTopPad', $IMCommand, 3, $steps['TextMask']);
	    //return $steps['TextMaskTopPad'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.($options['botpad']+$addPadding).' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMaskVPad', $IMCommand, 3, $steps['TextMaskTopPad']);
	    //return $steps['TextMaskVPad'];
	    
	    $cpoints = $this->getDistortionGuidelines($options, $steps['TextReliefWidth'], $steps['TextReliefHeight']);
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-virtual-pixel transparent ';
	    $IMCommand .= '	-background none ';
	    $IMCommand .= '	-distort Shepards \''.$cpoints['cpoints'].'\' ';
	    // DEBUG DISTORT (VISUALIZE DISTORTION GUIDELINES)
	    //$IMCommand .= '						-stroke red ';
	    //foreach($cpoints['cp'] as $point) {
	    //$IMCommand .= '						-draw "line '.$point.'" ';
	    //}
	    // END DEBUG DISTORT (VISUALIZE DISTORTION GUIDELINES)
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextReliefDistort', $IMCommand, 3, $steps['TextReliefVPad']);
	    //return $steps['TextReliefDistort'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-virtual-pixel transparent ';
	    $IMCommand .= '	-background none ';
	    $IMCommand .= '	-distort Shepards \''.$cpoints['cpoints'].'\' ';
	    // DEBUG DISTORT (VISUALIZE DISTORTION GUIDELINES)
	    //$IMCommand .= '						-stroke red ';
	    //foreach($cpoints['cp'] as $point) {
	    //$IMCommand .= '						-draw "line '.$point.'" ';
	    //}
	    // END DEBUG DISTORT (VISUALIZE DISTORTION GUIDELINES)
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMaskDistort', $IMCommand, 3, $steps['TextMaskVPad']);
	    //return $steps['TextMaskDistort'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistort']);
	    //$this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistortMore']);
	    //return $steps['TextReliefDistortExtent'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMaskDistortExtent', $IMCommand, 3, $steps['TextMaskDistort']);
	    //$this->exec($vars, $steps, 'TextMaskDistortExtent', $IMCommand, 3, $steps['TextMaskDistortMore']);
	    //return $steps['TextMaskDistortExtent'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.'band.png ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Band', $IMCommand, 2);
	    //return $steps['Band'];
	    
	    
	    $BandBG = array();
	    
	    if($options['swirlColor'] !== false) {
		if(count($options['swirlColor']) == 1) {
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.key($options['swirlColor']).'.png ';
		    $IMCommand .= '	png:- ';
		    $this->exec($vars, $steps, 'SwirlMask', $IMCommand, 2);
		    //return $steps['SwirlMask'];
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
		    $IMCommand .= '	xc:\''.reset($options['swirlColor']).'\' ';
		    $IMCommand .= '	png:- ';
		    
		    //$IMCommand .= '	-alpha Off ';
		    $IMCommand .= '	-compose CopyOpacity ';
		    $IMCommand .= '	-composite ';
		    $IMCommand .= '	png:- ';
		    $this->exec($vars, $steps, 'Swirl', $IMCommand, 3, $steps['SwirlMask']);
		    //var_dump($steps['errors']['Swirl']);die();
		    //var_dump($options['swirlColor']);die();
		    //header('Content-type: image/png');
		    //return $steps['Swirl'];
		} else {
		    foreach($options['swirlColor'] as $key=>$swirl) {
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl'.$key.'.png ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'SwirlMask'.$key, $IMCommand, 2);
			//return $steps['SwirlMask'];
			
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
			$IMCommand .= '	xc:\''.$swirl.'\' ';
			$IMCommand .= '	png:- ';
			
			//$IMCommand .= '	-alpha Off ';
			$IMCommand .= '	-compose CopyOpacity ';
			$IMCommand .= '	-composite ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'Swirl'.$key, $IMCommand, 3, $steps['SwirlMask'.$key]);
			//var_dump($steps['errors']['Swirl']);die();
			//var_dump($options['swirlColor']);die();
			//if($key==2)
			//header('Content-type: image/png');
			//return $steps['Swirl'.$key];
		    }
		    
		    
		    $swStepsOut = '';
		    
		    reset($options['swirlColor']);
		    $swStepsOut = $steps['Swirl'.key($options['swirlColor'])];
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	png:- ';
		    $i=0;
		    foreach($options['swirlColor'] as $key=>$swirl) {
			if($i!=0) {
			    $IMCommand .= '	png:- ';
			    $IMCommand .= '	-composite ';
			    $swStepsOut .= $steps['Swirl'.$key];
			}
			$i++;
		    }
		    $IMCommand .= '	png:- ';
		    $this->exec($vars, $steps, 'Swirl', $IMCommand, 3, $swStepsOut);
    
		    //var_dump($IMCommand);die();
		    //header('Content-type: image/png');
		    //return $steps['Swirl'];
		}
		
		$BandBG[] = $steps['Swirl'];
	    }
	    
	    if(!empty($options['glitter'])) {
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'glitter'.DIRECTORY_SEPARATOR.'glitter.png ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'Glitter', $IMCommand, 2);
		$BandBG[] = $steps['Glitter'];
		//return $steps['BandNoText'];
	    }
	    
	    if(!empty($BandBG)) {
		$bbInput = '';
		$i=0;
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		foreach($BandBG as $cmp) {
		    if(empty($i)) {
			$IMCommand .= '	png:- ';
		    } else {
			$IMCommand .= '	png:- ';
			$IMCommand .= '     -composite ';
		    }
		    $i++;
		    $bbInput .= $cmp;
		}
		
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'BandBG', $IMCommand, 3, $bbInput);
		//header('Content-type: image/png');
		//return $steps['BandBG'];
		
		$IMCommand = BIN_PATH.'composite ';
		$IMCommand .= '	png:- ';
		$IMCommand .= '	png:- ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'BandNoText', $IMCommand, 3, $steps['Band'].$steps['BandBG']);
		//return $steps['BandNoText'];
	    } else {
		$steps['BandNoText'] = $steps['Band'];
	    }
	    
	    //$IMCommand = BIN_PATH.'composite ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    //$this->exec($vars, $steps, 'BandNoText', $IMCommand, 3, $steps['Band'].$steps['BandBG']);
	    //header('Content-type: image/png');
	    //return $steps['BandNoText'];
	    
	    
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	-compose Dst_Out ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-alpha Set ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandNoText', $IMCommand, 3, $steps['TextMaskDistortExtent'].$steps['BandNoText']);
	    //return $steps['BandNoText'];
	    
	    
	    
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandComplete', $IMCommand, 3, $steps['TextReliefDistortExtent'].$steps['BandNoText']);
	
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	} else {
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.'band.png ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandComplete', $IMCommand, 2);
	    //return $steps['Band'];
	    
	    if($options['swirlColor'] !== false) {
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'swirl'.DIRECTORY_SEPARATOR.'swirl.png ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'SwirlMask', $IMCommand, 2);
		//return $steps['SwirlMask'];
		
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-size '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
		$IMCommand .= '	xc:\''.$options['swirlColor'].'\' ';
		$IMCommand .= '	png:- ';
		
		$IMCommand .= '	-alpha Off ';
		$IMCommand .= '	-compose CopyOpacity ';
		$IMCommand .= '	-composite ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'Swirl', $IMCommand, 3, $steps['SwirlMask']);
		//var_dump($steps['errors']['Swirl']);die();
		//var_dump($options['swirlColor']);die();
		//return $steps['Swirl'];
		
		$IMCommand = BIN_PATH.'composite ';
		$IMCommand .= '	png:- ';
		$IMCommand .= '	png:- ';
		$IMCommand .= '	png:- ';
		$this->exec($vars, $steps, 'BandComplete', $IMCommand, 3, $steps['BandComplete'].$steps['Swirl']);
		//return $steps['BandNoText'];
	    }
	}
	
	//header('Content-type: image/'.$options['format']);
        return $steps['BandComplete'];
        
    }
    
    ////////////////////////////////////////////////////////////////
    function getDistortionGuidelines($options, $w, $h) {
        $cpoints = '';
        $cp = array();

        for($i=0; $i*2<$w; $i+=3) {
                $point_offset = round($w/2)-$i;
                $pointstr = '';

                $point = $point_offset.',28 '.$point_offset.','.max(28-round(pow($i, 1.85)*0.002), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $pointstr .= '   ';

                $point = $point_offset.','.($h+28).' '.$point_offset.','.max(($h+28)-round(pow($i, 1.8)*0.0032), 0);
                $pointstr .= $point;
                $cp[] = $point;
                 
                $pointstr .= '   ';

                $point = ($w - $point_offset).',28 '.($w - $point_offset).','.max(28-round(pow($i, 1.85)*0.002), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $pointstr .= '   ';
                 
                $point = ($w - $point_offset).','.($h+28).' '.($w - $point_offset).','.max(($h+28)-round(pow($i, 1.8)*0.0032), 0);
                $pointstr .= $point;
                $cp[] = $point;

                $cpoints .= $pointstr;

                $cpoints .= ' ';
        }

        $distorted = true;
        return array('cp'=>$cp, 'cpoints'=>$cpoints);
    }
    
    /////////////////////////////////////////////////////////
    function getDistortionGuidelines2($options, $w, $h) {
	$cpoints = '';
	$cp = array();
	for($j=30; $j<$options['X']; $j+=5) {
            $pointstr = '';
                    
            $point = ' 0,'.$j.' 15'/*.round(pow(($j/5)*0.5, 3)*0.05)*/.','.$j;
            $pointstr .= $point;
            $cp[] = $point;
                    
            $pointstr .= '   ';
                    
            $point = ' '.$options['X'].','.$j.' '.($options['X']-15/*round(pow(($j/5)*0.5, 3)*0.05)*/).','.$j;
            $pointstr .= $point;
            $cp[] = $point;
                    
            $pointstr .= '   ';
                    
            $cpoints .= $pointstr;
             
            $cpoints .= ' ';
	}
        
        return array('cp'=>$cp, 'cpoints'=>$cpoints);
    }
}