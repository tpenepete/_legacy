<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Text extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
        //tpt_dump($options, true);
	//die('sdasdasd');
	//file_put_contents(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'kurec.txt', $options['text'], FILE_APPEND);
	//if($options['text'] == ' ') {
	//    var_dump($options['text']);die();
	//}
        $tpt_imagesurl = $vars['config']['images_url'];
        
        $types_module = getModule($vars, "BandType");
        $styles_module = getModule($vars, "BandStyle");
        $data_module = getModule($vars, "BandData");
        $messages_module = getModule($vars, "BandMessage");
        $colors_module = getModule($vars, "BandColor");
        $pfields_module = getModule($vars, "CustomProductField");
        $layers_module = getModule($vars, "BandPreviewLayer");
        
        $db = $vars['db']['handler'];
        
        $bdata = $data_module->typeStyle[$options['pgType']][$options['pgStyle']];
        $layer = $layers_module->moduleData['param'][$options['layer']];
        $mdata = $messages_module->moduleData['name'][$layer['param']];
        $type = $types_module->moduleData['id'][$options['pgType']];
        $style = $styles_module->moduleData['id'][$options['pgStyle']];
        $itext = $options[$pfields_module->moduleData['name'][$layer['param']]['preview_name']];
        

        $options['toppad'] = intval($bdata['preview_bg_toppadding'], 10);
        $options['rightpad'] = intval($bdata['preview_rightpadding'], 10);
        $options['bottompad'] = intval($bdata['preview_bg_bottompadding'], 10);
        $options['leftpad'] = intval($bdata['preview_leftpadding'], 10);
        $fullX = $bdata['preview_width'];
        $fullY = $bdata['preview_height'];
        $bgX = $fullX;
        $bgY = $fullY - (intval($bdata['preview_bg_toppadding'], 10) + intval($bdata['preview_bg_bottompadding'], 10));
        $sX = $bgX - (intval($bdata['preview_leftpadding'], 10) + intval($bdata['preview_rightpadding'], 10));
        $sY = $bgY - (intval($bdata['preview_toppadding'], 10) + intval($bdata['preview_bottompadding'], 10));
        $mX = $sX;
        $mY = $sY;
        $fmsg = reset($messages_module->ofront);
        $fmsg2 = end($messages_module->ofront);
        $bmsg = reset($messages_module->oback);
        $bmsg2 = end($messages_module->oback);
        $lines = 1;
        $back = 0;
        if(!empty($messages_module->ofront[$layer['param']]) && !empty($options[$fmsg2['var1']])) {
            $lines++;
        }
        if(!empty($messages_module->oback[$layer['param']]) && !empty($options[$bmsg2['var1']])) {
            $lines++;
        }
        if(!empty($options[$bmsg['var1']]) || !empty($options[$bmsg2['var1']])) {
            $back++;

        }

        if(!empty($back)) {
            $mX = intval(floor($mX/2), 10);
            if(empty($mdata['back'])) {
                $options['rightpad'] += $mX;
            } else {
                $options['leftpad'] += $mX;
            }
        }
        if($lines > 1) {
            $mY = intval(floor($mY/2), 10);
        }
        $options['X'] = $mX;
        $options['Y'] = $mY;
        
        $options['fullsizeX'] = $fullX;
        $options['fullsizeY'] = $fullY;
        if(empty($options['pgMessageColor'])) {
            $options['pgMessageColor'] = $options['pgMessageColor'];
        }
        
        //tpt_dump($itext, true);
        unset($options['text']);
        $options['text'] = ''.escapeshellarg(str_replace('%', '\\%', str_replace('@', '\\@', str_replace('\\', '\\\\', $itext)))).'';
        if(strlen($itext) > 20) {
                $emboss = '1';
        } else {
                $emboss = '1';
        }
        $options['extrude'] = $emboss;
	$ttext = trim($itext);
        $options['linespacing'] = '0';
	
        //tpt_dump($options, true);
	
	if(empty($ttext)) {
	    //die('!>!>!>!>!');
	    $steps['BandComplete'] = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png');
	    
	    return $steps['BandComplete'];
	}
        
	
	
	    if(!empty($ttext)) {
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //if(!empty($options['pointsize'])) {
	    $IMCommand .= '	-size 3000x200 ';
		$IMCommand .= '	-pointsize 48 ';
	    //} else {
		//$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    //}
	    $IMCommand .= '	-background black ';
	    $IMCommand .= '	-fill white ';
	    $IMCommand .= '	-stroke none ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['pgFont'].' ';
	    $IMCommand .= '			 label:'.$options['text'].' ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    //var_dump($IMCommand);die();
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 2);
	    //header('Content-type: image/png');
	    //die($steps['WhiteTextOnBlackBG']);
	    //return $steps['WhiteTextOnBlackBG'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-adaptive-resize '.$options['X'].'x'.$options['Y'].' ';
	    //$IMCommand .= '	-adaptive-resize ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    //var_dump($IMCommand);die();
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
	    //header('Content-type: image/png');
	    //return $steps['WhiteTextOnBlackBG'];
	    }
	    
	    
	    	    
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
	    $this->exec($vars, $steps, 'BlackTextNoBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
	    //var_dump($steps['errors']['TextMask']);die();
	    //header('Content-type: image/png');
	    //return $steps['BlackTextNoBG'];
	    
	    
	    $BandText = array();
	    
	    
	    if(!empty($style['message_color'])) {
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
	    //header('Content-type: image/png');
	    //return $steps['TextMaskMask'];
	    
	    if(empty($options['pgMessageColor'])) {
                //$tc = rgb2hex2rgb(DEFAULT_MESSAGE_COLOR);
                //$textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                $options['pgMessageColor'] = DEFAULT_MESSAGE_COLOR;
	    }
            $cprops = $colors_module->getColorProps($vars, $options['pgMessageColor']);
            $opts['type'] = $cprops['colortypename'];//intval($data['pg_x'], 10);
            $opts['fullsizeX'] = $options['fullsizeX'];//intval($data['pg_x'], 10);
            $opts['fullsizeY'] = $options['fullsizeY'];//intval($data['pg_y'], 10);
            $opts['color'] = $options['pgMessageColor'];
            $opts['pg_x'] = $options['X'];
            $opts['pg_y'] = $options['Y'];
            //die();
            //tpt_dump($opts, true);
            $getPreview = self::generatePreview($vars, $opts);
            $options['pgMessageColor'] = $getPreview;
	    //var_dump($options['pgMessageColor']);die();
	    
            $xTextInput = $options['pgMessageColor'];
	    $xTextInput .= $steps['TextMaskMask'];
            
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMask', $IMCommand, 3, $xTextInput);
	    //var_dump($steps['errors']['TextMask']);die();
	    //header('Content-type: image/png');
	    //return $steps['TextMask'];
	    
	    $BandText['TextMask'] = $steps['TextMask'];
	    }
	    
	    
	    if(intval($style['message_relief'], 10) !== 0) {
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
            //die();
	    //return $steps['TextHighlightMask'];
	    
		    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    switch(intval($options['pgStyle'], 10)) {
		case 3 :
		case 4 :
	    $IMCommand .= '	xc:\'black\' ';
		    break;
		default :
		    //var_dump($options['cut_away']);die();
	     //if((($options['pgStyle'] != 7) && !empty($options['cut_away'])) || (($options['pgStyle'] == 7) && isset($options['pgType']) && (($options['pgType'] != 1) && ($options['pgType'] != 5))) || (($options['pgStyle'] == 7) && isset($options['pgType']) && (($options['pgType'] == 1) || (($options['pgType'] == 5) && !empty($options['invert_dual']))))) {
	     if((($options['pgType'] == 5) && ($options['pgStyle'] == 7) && empty($options['invert_dual'])) || (($options['pgType'] == 5) && ($options['pgStyle'] == 6) && !empty($options['cut_away'])) || ($options['pgStyle'] == 8)) {
	    $IMCommand .= '	xc:\'black\' ';
	     } else {
	    $IMCommand .= '	xc:\'white\' ';
	     }
		    break;
	    }
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	 -geometry -0+'.$options['extrude'].' ';
	    $IMCommand .= '	-alpha Off ';
	    $IMCommand .= '	-compose CopyOpacity ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextHighlightFull', $IMCommand, 3, $steps['TextHighlightMask']);
	    //var_dump($IMCommand);die();
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
	    
	    $BandText['TextHighlight'] = $steps['TextHighlight'];
	    
	    
	    
	    
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
	    switch(intval($options['pgStyle'], 10)) {
		case 3 :
		case 4 :
	    $IMCommand .= '	xc:\'white\' ';
		    break;
		default :
	     if((($options['pgType'] == 5) && ($options['pgStyle'] == 7) && empty($options['invert_dual'])) || (($options['pgType'] == 5) && ($options['pgStyle'] == 6) && !empty($options['cut_away'])) || ($options['pgStyle'] == 8)) {
	    $IMCommand .= '	xc:\'white\' ';
	     } else {
	    $IMCommand .= '	xc:\'black\' ';
	     }
		    break;
	    }
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
	    
	    $BandText['TextShadow'] = $steps['TextShadow'];
	    }
	    
	    //if(($options['pgStyle'] == 8)) {
		//unset($BandText['TextShadow']);
	    //}
	    
	    //var_dump();
	    
	    if(!empty($BandText) && (count($BandText) > 1)) {
		$bbInput = '';
		$i=0;
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
		$IMCommand .= '	xc:transparent ';
		$IMCommand .= '	-gravity center ';
		foreach($BandText as $key=>$cmp) {
		    //if($key != 'TextMask') {
		    if($key == 'TextShadow') {
		    $IMCommand .= '	-geometry -0+'.$options['extrude'].' ';
		    $IMCommand .= '	png:-  ';
		    } else {
		    $IMCommand .= '	-geometry -0+0 ';
		    $IMCommand .= '	png:- ';    
		    }
		    $IMCommand .= '     -composite ';
		    $bbInput .= $cmp;
		    //}
		}
		$IMCommand .= '	-trim ';
		$IMCommand .= '	png:- ';
		//var_dump($IMCommand);die();
		$this->exec($vars, $steps, 'BandText', $IMCommand, 3, $bbInput);
	    } else {
		
		$steps['BandText'] = $steps['TextMask'];
		//return $steps['BandText'];
	    }
	    //header('Content-type: image/png');
	    //return $steps['BandText'];
	    
	    
	    //return $steps['BandText'];
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	+repage ';
	    $this->exec($vars, $steps, 'BandTextTrim', $IMCommand, 3, $steps['BandText']);
	    //var_dump($IMCommand);die();
	    //var_dump($steps);die();
	    //return $steps['BandTextTrim'];
	    
	    //$steps['BandTextTrim'] = $steps['BandText'];
	    
            /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-format "%w" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'BandTextWidth', $IMCommand, 3, $steps['BandTextTrim']);
	    //return $steps['TextReliefWidth'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-format "%h" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'BandTextHeight', $IMCommand, 3, $steps['BandTextTrim']);
	    //return $steps['TextReliefHeight'];
	    //$addPadding = abs(round(($options['Y'] - $steps['BandTextHeight'] - 6)/2));
	    */
            
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$options['toppad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextTopPad', $IMCommand, 3, $steps['BandTextTrim']);
	    //header('Content-type: image/png');
	    //return $steps['BandTextTrim'];
	    //return $steps['BandTextTopPad'];
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.($options['bottompad']).' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextBottomPad', $IMCommand, 3, $steps['BandTextTopPad']);
	    //return $steps['TextReliefVPad'];
            
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity east ';
	    $IMCommand .= '	-splice '.($options['rightpad']).'x0 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextRightPad', $IMCommand, 3, $steps['BandTextBottomPad']);
            
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	 ';
	    $IMCommand .= '	-splice '.($options['leftpad']).'x0 ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextLeftPad', $IMCommand, 3, $steps['BandTextRightPad']);
            //return $steps['BandTextRightPad'];
            //var_dump($steps['BandTextRightPad']);die();
            //var_dump($IMCommand);die();
            //tpt_dump($steps, true);
            
            /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-format "%w" ';
	    $IMCommand .= '	info:';
	    $this->exec($vars, $steps, 'BandTextWidth', $IMCommand, 3, $steps['BandTextRightPad']);
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	--version ';
	    $this->exec($vars, $steps, 'BandTextWidth', $IMCommand, 3, $steps['BandTextRightPad']);
	    die($steps['BandTextWidth']);
            */

	    

            /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextRightPad']);
	    */
	    //$this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistortMore']);
	    //header('Content-type: image/png');
	    //return $steps['BandTextVPad'];
	    
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextTrim']);
	    */
	    
	    $steps['BandComplete'] = $steps['BandTextLeftPad'];
	    //$steps['BandComplete'] = $steps['BandTextLeftPad'];
	    //$steps['BandComplete'] = $steps['BandTextRightPad'];
	    
	    
	
	if(empty($options['text'])) {
	    $steps['BandComplete'] = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png');
	} else {
	    //$cfile = $options['cfile'];
	    //var_dump($cfile);die();
	    if(!empty($options['cfile'])) {
		file_put_contents($options['cfile'], $steps['BandComplete']);
	    }
	}
	
	//header('Content-type: image/'.$options['format']);
	if(($options['pgStyle'] != 7)) {
	//header('Content-type: image/png');
	}

	//die();
        return $steps['BandComplete'];
        
    }
    
}