<?php

defined('TPT_INIT') or die('access denied');

class tpt_gclass_Plain extends tpt_PreviewGenerator {
    
    
    function __construct() {}
    
    function generate(&$vars, $options, &$steps) {
	//die('sdasdasd');
	//file_put_contents(TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'kurec.txt', $options['text'], FILE_APPEND);
	//if($options['text'] == ' ') {
	//    var_dump($options['text']);die();
	//}


	
	$ttext = trim($options['utext']);

		//tpt_dump($ttext!=='');
		//tpt_dump(!is_null($ttext));
		//tpt_dump($options, true);
	if(empty($options['lclipart']) && empty($options['rclipart']) && empty($options['lclipart_c']) && empty($options['rclipart_c']) && (($ttext=='') || is_null($ttext))) {
	    //die('!>!>!>!>!');
	    $steps['BandComplete'] = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png');
	    
	    return $steps['BandComplete'];
	}
	//tpt_dump($options, true);
	//var_dump($options);//die();
	
	/*
        $format = 'jpg:-';
        switch($options['format']) {
            case 'png' :
                $format = 'png:-';
                break;
            default:
                $format = 'jpg:-';
                break;
        }
	*/
	
	//return $options['textColor'];//die();
        
        //$path = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin:/usr/lib64"; ';
        
        //$IMCommand = 'export PATH="/usr/local/jdk/bin:/usr/lib64/qt-3.3/bin:/usr/lib/courier-imap/sbin:/usr/lib/courier-imap/bin:/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin:/usr/X11R6/bin:/root/bin";';
        //$IMCommand = 'export PATH="/usr/local/bin"; echo $PATH';
        //$this->exec($vars, $steps, 'path', $IMCommand, 2);
        
	/*
	if(!empty($options['text'])) {
	*/
	//var_dump($options['pointsize']);die();
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    if(!empty($options['pointsize'])) {
		$IMCommand .= '	-pointsize '.$options['pointsize'].' ';
	    } else {
		$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    }
	    $IMCommand .= '	-background black ';
	    $IMCommand .= '	-fill white ';
	    $IMCommand .= '	-stroke none ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '			 label:'.$options['text'].' ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    //var_dump($IMCommand);die();
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 2);
	    header('Content-type: image/png');
	    return $steps['WhiteTextOnBlackBG'];
	    */

		/*
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	--version ';
		//tpt_dump($IMCommand, true);
		$this->exec($vars, $steps, 'Version', $IMCommand, 2);
		tpt_dump($steps['Version'], true);
		*/

		//tpt_dump($ttext);
		//tpt_dump(($ttext !== ''));
		//tpt_dump(!is_null($ttext));
	    if(($ttext !== '') && !is_null($ttext)) {
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //if(!empty($options['pointsize'])) {
	    //$IMCommand .= '	-size x'.$options['Y'];
		$IMCommand .= '	-pointsize 40 ';
	    //} else {
		//$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    //}
		//$IMCommand .= '	+antialias ';
	    $IMCommand .= '	-background black ';
	    $IMCommand .= '	-fill white ';
	    $IMCommand .= '	-stroke none ';
		//$IMCommand .= '	+repage ';

	    //$IMCommand .= '	-gravity center';
	    //$IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '			 label:'.$options['text'].' ';
		//$IMCommand .= '	-repage ';
	    $IMCommand .= '	-trim ';
		//$IMCommand .= '	+repage ';

	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    //tpt_dump($IMCommand, true);
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG0', $IMCommand, 2);
	    //header('Content-type: image/png');
	    //return $steps['WhiteTextOnBlackBG'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-resize '.$options['X'].'x'.$options['Y'].' ';
	    //$IMCommand .= '	-adaptive-resize ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    //var_dump($IMCommand);die();
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG0']);
	    //header('Content-type: image/png');
	    //return $steps['WhiteTextOnBlackBG'];
	    }
	    
	    //var_dump($options['lclipart']);die();
	    if(!empty($options['lclipart']) || !empty($options['rclipart']) || !empty($options['lclipart_c']) || !empty($options['rclipart_c'])) {
		/*
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	- ';
		$IMCommand .= '	-format "%w" ';
		$IMCommand .= '	info:';
		$this->exec($vars, $steps, 'LabelWidth', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
		//return $steps['TextReliefWidth'];
		*/
		
		if(($ttext!=='') && !is_null($ttext)) {
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	- ';
		$IMCommand .= '	-format "%h" ';
		$IMCommand .= '	info:';
		$this->exec($vars, $steps, 'LabelHeight', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
		//return $steps['TextReliefWidth'];
		$steps['LabelHeight'] = trim($steps['LabelHeight']);
		$steps['LabelHeight'] = (empty($steps['LabelHeight']) || ($steps['LabelHeight'] == 1)?$options['Y']:$steps['LabelHeight']);
		} else {
		    $steps['LabelHeight'] = $options['Y'];
		}
		//tpt_dump($steps['LabelHeight'], true);
		
		
		$textelms = array(0=>'', 1=>'', 2=>'');
		$lwid = $options['X'];
			/*
		$ox = $options['X'];
		if(!empty($options['lclipart']) && !empty($options['rclipart']) && empty($options['text'])) {
			$ox = floor($options['X']/2);
		}

		if(!empty($options['lclipart_c']) && !empty($options['rclipart_c']) && empty($options['text'])) {
			$ox = floor($options['X']/2);
		}
			*/
		//tpt_dump($options['X']);
		//tpt_dump($lwid, true);
		$telms = 0;
		if(!empty($options['lclipart'])) {
			$telms++;
		}
		if(!empty($options['rclipart'])) {
			$telms++;
		}

		if(!empty($options['lclipart_c'])) {
			$telms++;
		}
		if(!empty($options['rclipart_c'])) {
			$telms++;
		}
		//tpt_dump($telms, true);

		$steps['WhiteTextOnBlackBGW'] = 0;
		if(($ttext!=='') && !is_null($ttext)) {
			/*
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-size '.$lwid.'x'.$options['Y'].' ';
		$IMCommand .= '	-background black ';
		$IMCommand .= '	-fill white ';
		$IMCommand .= '	-stroke none ';
		$IMCommand .= '	-gravity center ';
		//$IMCommand .= '	-trim ';
		//$IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
		$IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
		$IMCommand .= '			 label:'.$options['text'].' ';
		$IMCommand .= '	png:- ';
		//$IMCommand .= '	bahur.png ';
		//tpt_dump($IMCommand, true);
		$this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 2);
		*/
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
			$IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
			$IMCommand .= '			 label:'.$options['text'].' ';
			$IMCommand .= '	-trim ';
			$IMCommand .= '	png:- ';
			//$IMCommand .= '	bahur.png ';
			//var_dump($IMCommand);die();
			$this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 2);
			//header('Content-type: image/png');
			//return $steps['WhiteTextOnBlackBG'];

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	png:- ';
			$IMCommand .= '	-adaptive-resize '.($options['X']-$telms*$options['Y']).'x'.$options['Y'].' ';
			//$IMCommand .= '	-adaptive-resize ';
			$IMCommand .= '	-trim ';
			$IMCommand .= '	png:- ';
			//$IMCommand .= '	bahur.png ';
			//var_dump($IMCommand);die();
			$this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);

			$textelms[1] = $steps['WhiteTextOnBlackBG'];

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%w" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'WhiteTextOnBlackBGW', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);

			/*
			if(($steps['WhiteTextOnBlackBGW'] > $options['X']-6) && !empty($telms)) {
				$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
				$IMCommand .= '	png:- ';
				$IMCommand .= '	-adaptive-resize '.$options['X'].'x'.$options['Y'].' ';
				//$IMCommand .= '	-adaptive-resize ';
				$IMCommand .= '	-trim ';
				$IMCommand .= '	png:- ';
				//$IMCommand .= '	bahur.png ';
				//var_dump($IMCommand);die();
				$this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
			}
			*/

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%h" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'WhiteTextOnBlackBGH', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
		}

		if(empty($steps['WhiteTextOnBlackBGH'])) {
			$steps['WhiteTextOnBlackBGH'] = $options['Y'];
		}

		$lwid -= $steps['WhiteTextOnBlackBGW'];
		//tpt_dump($lwid, true);

		if(!empty($options['lclipart'])) {
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= ' '.$options['lclipart'].' ';
		    $IMCommand .= '	-adaptive-resize '.max(floor(($options['X']-$steps['WhiteTextOnBlackBGW'])/$telms), $options['Y']).'x'.$steps['WhiteTextOnBlackBGH'];
		    $IMCommand .= '	png:-';
		    $this->exec($vars, $steps, 'LeftClip0', $IMCommand, 2);
		    //tpt_dump($IMCommand, true);//die();
		    //var_dump($IMCommand);//die();
		    //var_dump($steps);die();
		    //header('Content-type: image/png');
		    //return $steps['LeftClip'];
		    //if(empty($ttext)) {
		    //    //var_dump($options);
		    //	var_dump($IMCommand);
		    //    die();
		    //	header('Content-type: image/png');
		    //	die($steps['LeftClip']);
		    //}
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	png:- ';
		    $IMCommand .= '	-channel RGB ';
		    $IMCommand .= '	-negate ';
		    $IMCommand .= '	png:- ';
		    $this->exec($vars, $steps, 'LeftClip1', $IMCommand, 3, $steps['LeftClip0']);
		    //if(empty($ttext)) {
		    //    //var_dump($options);
		    //    //die();
		    //	header('Content-type: image/png');
		    //	die($steps['LeftClip']);
		    //}
		    //header('Content-type: image/png');
		    //return $steps['LeftClip'];
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	- ';
		    $IMCommand .= '	-format "%w" ';
		    $IMCommand .= '	info:';
		    $this->exec($vars, $steps, 'LClipW', $IMCommand, 3, $steps['LeftClip1']);
		    //return $steps['TextReliefWidth'];
		    
		    /*
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	- ';
		    $IMCommand .= '	-format "%h" ';
		    $IMCommand .= '	info:';
		    $this->exec($vars, $steps, 'LClipH', $IMCommand, 3, $steps['LeftClip']);
		    //return $steps['TextReliefWidth'];
		    */
		    
		    $lwid -= $steps['LClipW'];
		    
		    $textelms[0] = $steps['LeftClip1'];
		}

		//tpt_dump($options['lclipart_c'], true);
		if(!empty($options['lclipart_c'])) {

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= ' '.$options['lclipart_c'].' ';
			$IMCommand .= '	-adaptive-resize '.max(floor(($options['X']-$steps['WhiteTextOnBlackBGW'])/$telms), $options['Y']).'x'.$steps['WhiteTextOnBlackBGH'];
			$IMCommand .= '	png:-';
			$this->exec($vars, $steps, 'LeftClip0c', $IMCommand, 2);
			//var_dump($IMCommand);//die();
			//var_dump($steps);die();
			//header('Content-type: image/png');
			//return $steps['LeftClip'];
			//if(empty($ttext)) {
			//    //var_dump($options);
			//	var_dump($IMCommand);
			//    die();
			//	header('Content-type: image/png');
			//	die($steps['LeftClip']);
			//}

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	png:- ';
			$IMCommand .= '	-channel RGB ';
			$IMCommand .= '	-negate ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'LeftClip1c', $IMCommand, 3, $steps['LeftClip0c']);
			//if(empty($ttext)) {
			//    //var_dump($options);
			//    //die();
			//	header('Content-type: image/png');
			//	die($steps['LeftClip']);
			//}
			//header('Content-type: image/png');
			//return $steps['LeftClip'];

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%w" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'LClipW', $IMCommand, 3, $steps['LeftClip1c']);
			//return $steps['TextReliefWidth'];
			//tpt_dump($IMCommand, true);
			//return $steps['LeftClip'];

			/*
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%h" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'LClipH', $IMCommand, 3, $steps['LeftClip']);
			//return $steps['TextReliefWidth'];
			*/

			$lwid -= $steps['LClipW'];

			$textelms[0] = $steps['LeftClip1c'];
		}
		
		//var_dump($options['rclipart']);die();
			//tpt_dump($options);
			//tpt_dump($options, true);
		if(!empty($options['rclipart'])) {
			//tpt_dump($options['rclipart'], true);
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= ' '.$options['rclipart'].' ';
		    $IMCommand .= '	-adaptive-resize '.max(floor(($options['X']-$steps['WhiteTextOnBlackBGW'])/$telms), $options['Y']).'x'.$steps['WhiteTextOnBlackBGH'];
		    $IMCommand .= '	png:-';
		    $this->exec($vars, $steps, 'RightClip0', $IMCommand, 2);
			//return $steps['RightClip0'];
		    //tpt_dump($IMCommand, true);
		    //header('Content-type: image/png');
		    //return $steps['RightClip'];
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	png:- ';
		    $IMCommand .= '	-channel RGB ';
		    $IMCommand .= '	-negate ';
		    $IMCommand .= '	png:- ';
		    $this->exec($vars, $steps, 'RightClip1', $IMCommand, 3, $steps['RightClip0']);
			//if(isDump()) {
			//	return $steps['RightClip1'];
			//}
		    //header('Content-type: image/png');
		    //var_dump($steps);die();
		    //return $steps['RightClip'];
		    
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	- ';
		    $IMCommand .= '	-format "%w" ';
		    $IMCommand .= '	info:';
		    $this->exec($vars, $steps, 'RClipW', $IMCommand, 3, $steps['RightClip1']);
		    //return $steps['TextReliefWidth'];
		    //return $steps['RightClip'];

		    /*
		    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		    $IMCommand .= '	- ';
		    $IMCommand .= '	-format "%h" ';
		    $IMCommand .= '	info:';
		    $this->exec($vars, $steps, 'RClipH', $IMCommand, 3, $steps['RightClip']);
		    //return $steps['TextReliefWidth'];
		    */
		    
		    $lwid -= $steps['RClipW'];
		    
		    $textelms[2] = $steps['RightClip1'];
			//return $steps['RightClip1'];
		    
		}


		if(!empty($options['rclipart_c'])) {
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= ' '.$options['rclipart_c'].' ';
			$IMCommand .= '	-adaptive-resize '.max(floor(($options['X']-$steps['WhiteTextOnBlackBGW'])/$telms), $options['Y']).'x'.$steps['WhiteTextOnBlackBGH'];
			$IMCommand .= '	png:-';
			$this->exec($vars, $steps, 'RightClip0c', $IMCommand, 2);
			//var_dump($steps);die();
			//header('Content-type: image/png');
			//return $steps['RightClip'];

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	png:- ';
			$IMCommand .= '	-channel RGB ';
			$IMCommand .= '	-negate ';
			$IMCommand .= '	png:- ';
			$this->exec($vars, $steps, 'RightClip1c', $IMCommand, 3, $steps['RightClip0c']);
			//header('Content-type: image/png');
			//var_dump($steps);die();
			//return $steps['RightClip'];

			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%w" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'RClipW', $IMCommand, 3, $steps['RightClip1c']);
			//return $steps['TextReliefWidth'];

			/*
			$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
			$IMCommand .= '	- ';
			$IMCommand .= '	-format "%h" ';
			$IMCommand .= '	info:';
			$this->exec($vars, $steps, 'RClipH', $IMCommand, 3, $steps['RightClip']);
			//return $steps['TextReliefWidth'];
			*/

			$lwid -= $steps['RClipW'];

			$textelms[2] = $steps['RightClip1c'];

		}
		
		//return $steps['WhiteTextOnBlackBG'];

		//return $steps['WhiteTextOnBlackBG'];
		
		//var_dump($steps['LeftClip']);
		//var_dump($steps['WhiteTextOnBlackBG']);die();
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
		$IMCommand .= '	-background black ';
		//$IMCommand .= '	xc:transparent ';\
		$lInput = '';
		foreach($textelms as $elm) {
		    if(!empty($elm)) {
			$IMCommand .= '	-gravity Center ';
			$IMCommand .= '	png:- ';
			$lInput .= $elm;
			//$IMCommand .= '	-composite ';
			//$IMCommand .= '	-gravity East ';
			//$IMCommand .= '	png:- ';
			//$IMCommand .= '	-composite ';
		    }
		}
		$IMCommand .= '	+append ';
		$IMCommand .= '	-trim ';
		$IMCommand .= '	png:- ';
		//$IMCommand .= '	bahur.png ';
			//tpt_dump($IMCommand, true);
		$this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $lInput);
		//var_dump($steps['errors']);die();
		//header('Content-type: image/png');
		//return $steps['WhiteTextOnBlackBG'];
	    }
	    
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-resize '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'WhiteTextOnBlackBG', $IMCommand, 3, $steps['WhiteTextOnBlackBG']);
	    //header('Content-type: image/png');
	    //return $steps['WhiteTextOnBlackBG'];
	    */
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-fill black ';
	    $IMCommand .= '	-stroke none ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	-interline-spacing '.$options['linespacing'].' ';
	    $IMCommand .= '	-font '.FONTS_PATH.DIRECTORY_SEPARATOR.$options['font'].' ';
	    $IMCommand .= '			 label:'.$options['text'].' ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	bahur.png ';
	    $this->exec($vars, $steps, 'BlackTextNoBG', $IMCommand, 2);
	    //return $steps['BlackTextNoBG'];
	    */
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //if(!empty($options['pointsize'])) {
	    //	$IMCommand .= '	-pointsize '.$options['pointsize'].' ';
	    //} else {
		$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    //}
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
		//if(isDump()) {
		//	return $steps['BlackTextNoBG'];
		//}
	    
	    
	    $BandText = array();
	    //tpt_dump($options['bandStyle'], true);
	    
	    if((empty($options['bandStyle']) || ($options['bandStyle'] == 2) || ($options['bandStyle'] == 4) || ($options['bandStyle'] == 5) || (($options['bandStyle'] == 7))/* || (($options['bandStyle'] == 8))*/ || ($options['bandStyle'] == 16) )) {
		//die('asdasdasdasdasd');
		//tpt_dump($options['textColor'], true);
		//return $options['textColor'];
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
	    
	    
	    if(empty($options['textColor'])) {
                $tc = rgb2hex2rgb(DEFAULT_MESSAGE_COLOR);
                $textColor = 'rgb('.$tc['r'].','.$tc['g'].','.$tc['b'].')';
                $options['textColor'] = $textColor;
	    }
	    //var_dump($options['textColor']);die();
	    
	    $xTextInput = $steps['TextMaskMask'];
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    //if(($options['bandStyle'] == 7)|| (($options['bandStyle'] == 8))) {
	    $IMCommand .= '	png:- ';
	    $xTextInput = $options['textColor'].$xTextInput;
	    //} else {
	    //$IMCommand .= '	xc:\''.$options['textColor'].'\' ';
	    //}
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
	    
	    
	    if(($options['bandStyle'] == 1) || ($options['bandStyle'] == 2) || ($options['bandStyle'] == 3) || ($options['bandStyle'] == 4) || ($options['bandStyle'] == 6) || ($options['bandStyle'] == 7) || ($options['bandStyle'] == 8) || ($options['bandStyle'] == 11)) {
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
	    switch(intval($options['bandStyle'], 10)) {
		case 3 :
		case 4 :
	    $IMCommand .= '	xc:\'black\' ';
		    break;
		default :
		    //var_dump($options['cut_away']);die();
	     //if((($options['bandStyle'] != 7) && !empty($options['cut_away'])) || (($options['bandStyle'] == 7) && isset($options['bandType']) && (($options['bandType'] != 1) && ($options['bandType'] != 5))) || (($options['bandStyle'] == 7) && isset($options['bandType']) && (($options['bandType'] == 1) || (($options['bandType'] == 5) && !empty($options['invert_dual']))))) {
	     if((($options['bandStyle'] == 7) && ($options['bandType'] == 5) && empty($options['invert_dual'])) || (($options['bandStyle'] == 6) && !empty($options['cut_away'])) || ($options['bandStyle'] == 8)) {
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
	    switch(intval($options['bandStyle'], 10)) {
		case 3 :
		case 4 :
	    $IMCommand .= '	xc:\'white\' ';
		    break;
		default :
	     if((($options['bandStyle'] == 7) && ($options['bandType'] == 5) && empty($options['invert_dual'])) || (($options['bandStyle'] == 6) && !empty($options['cut_away'])) || ($options['bandStyle'] == 8)) {
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
	    
	    //if(($options['bandStyle'] == 8)) {
		//unset($BandText['TextShadow']);
	    //}
	    
	    //var_dump();
	    //var_dump($BandText);die();
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
		//tpt_dump($this->getImageMagickVersion($vars), true);
		//tpt_dump($bbInput, true);
	    } else {
		/*
		$IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
		//$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
		//$IMCommand .= '	xc:transparent ';
		//$IMCommand .= '	-gravity center ';
		$IMCommand .= '	png:-  ';
		$IMCommand .= '	-trim ';
		$IMCommand .= '	png:- ';
		//var_dump($IMCommand);die();
		$this->exec($vars, $steps, 'BandText', $IMCommand, 3, $steps['TextMask']);
		*/
		
		$steps['BandText'] = isset($steps['TextMask'])?$steps['TextMask']:'';
		//return $steps['BandText'];
	    }
	    //header('Content-type: image/png');
	    //return $steps['BandText'];
	    
	    /*
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
	    //var_dump($IMCommand);die();
	    $this->exec($vars, $steps, 'TextReliefTrim', $IMCommand, 3, $steps['TextHighlight'].$steps['TextShadow']);
	    //header('Content-type: image/png');
	    //return $steps['TextReliefTrim'];
	    */
	    
	    
	    
	    
	    
	    //return $steps['BandText'];
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '	-size '.$options['X'].'x'.$options['Y'].' ';
	    //$IMCommand .= '	xc:transparent ';
	    //$IMCommand .= '	-gravity center ';
	    //for($i=1;$i<=$emboss;$i++) {
	    //}
	    //for($i=1;$i<=$emboss;$i++) {
	    //}
	    //$IMCommand .= '	png:- -geometry -0+'.$options['extrude'].' -composite ';
	    //$IMCommand .= '	png:- -composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	+repage ';
	    $this->exec($vars, $steps, 'BandTextTrim', $IMCommand, 3, $steps['BandText']);
	    //var_dump($IMCommand);die();
	    //var_dump($steps);die();
	    //return $steps['BandTextTrim'];
		//if(isDump()) {
		//	return $steps['BandTextTrim'];
		//}
	    
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-clip png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'clipping', $IMCommand, 3, $steps['BandText']);
	    //return $steps['BandText'];
	    var_dump($steps);die();
	    return $steps['clipping'];
	    */
	    
	    /*
	    $IMCommand = BIN_PATH.'identify ';
	    $IMCommand .= '	-verbose ';
	    $IMCommand .= '	png:- ';
	    //$IMCommand .= '	+repage ';
	    $this->exec($vars, $steps, 'someinfo', $IMCommand, 3, $steps['BandText']);
	    //var_dump($IMCommand);die();
	    //var_dump($steps);die();
	    var_dump($steps['someinfo']);die();
	    */
	    
	    //$steps['BandTextTrim'] = $steps['BandText'];
	    
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
	    $addPadding = abs(round(($options['Y'] - $steps['BandTextHeight'] - 6)/2));
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity north ';
	    $IMCommand .= '	-splice 0x'.$options['toppad'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextTopPad', $IMCommand, 3, $steps['BandTextTrim']);
	    //header('Content-type: image/png');
	    //return $steps['BandTextTrim'];
	    //return $steps['BandTextTopPad'];
	    
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-splice 0x'.($options['botpad']+$addPadding).' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextVPad', $IMCommand, 3, $steps['BandTextTopPad']);
	    //return $steps['TextReliefVPad'];
	    
	    /*
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
	    
	    //var_dump($options['fullsizeX']);
	    //var_dump($options['fullsizeY']);
	    //die();
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity south ';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'TextMaskExtent', $IMCommand, 3, $steps['TextMaskVPad']);
	    //$this->exec($vars, $steps, 'TextMaskDistortExtent', $IMCommand, 3, $steps['TextMaskDistortMore']);
	    //header('Content-type: image/png');
	    //return $steps['TextMaskExtent'];
	    */
	    

	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextVPad']);
	    //$this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistortMore']);
	    //header('Content-type: image/png');
	    //return $steps['BandTextVPad'];
	    
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center';
	    $IMCommand .= '	-resize '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextVPad']);
	    //$this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistortMore']);
	    //header('Content-type: image/png');
	    //return $steps['TextReliefExtent'];
	    */
	    
	    /*
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.'plain.png ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'Band', $IMCommand, 2);
	    //return $steps['Band'];
	    
	    
	    $steps['BandNoText'] = $steps['Band'];
	    
	    
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
	    $this->exec($vars, $steps, 'BandNoText', $IMCommand, 3, $steps['TextMaskExtent'].$steps['BandNoText']);
	    //return $steps['BandNoText'];
	    
	    
	    
	    $IMCommand = BIN_PATH.'composite ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandComplete', $IMCommand, 3, $steps['TextReliefExtent'].$steps['BandNoText']);
	    */
	    //var_dump($options['bandStyle']);
	    //var_dump($options['bandType']);
	    //var_dump($options['bandColor']);
	    //die();
	    
	    /*
	    if(($options['bandStyle'] == 7) && isset($options['bandType']) && ($options['bandType'] == 5)) {
		//die('asdasdasdas');
	    $types_module = getModule($vars, "BandType");
	    //var_dump($options['pgType']);die();
	    //var_dump($types_module->moduleData['id'][$options['pgType']]['preview_folder']);die();
            $pgDir = $types_module->moduleData['id'][$options['bandType']]['preview_folder'];
	    
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
	    
	    
	    
	    
	    if(!empty($options['invert_dual'])) {
            $mask = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'dual-layer-mask.png');
            //$shade = file_get_contents(TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'dual-layer-shade.png');
		
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	-size 578x62 ';
	    $IMCommand .= '	xc:\''.reset($options['invColor']).'\' ';
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
	    //$IMCommand .= '	-trim ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'DualSlapInner', $IMCommand, 3, $mask);
	    //if($_SERVER['REMOTE_ADDR'])
	    //var_dump($options);die();
	    //var_dump($IMCommand);die();
	    //var_dump($steps['errors']);die();
	    //return $steps['SomeStuff'];
	    $overlay = $steps['DualSlapInner'];
	    }
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-gravity center ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-composite ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $overlay.$steps['BandTextTrim']);
	    //return $steps['BandTextExtent'];
	    
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextExtent']);
	    } else {
	    */
	    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    $IMCommand .= '	png:- ';
	    $IMCommand .= '	-background transparent ';
	    $IMCommand .= '	-gravity center';
	    $IMCommand .= '	-extent '.$options['fullsizeX'].'x'.$options['fullsizeY'].' ';
	    $IMCommand .= '	png:- ';
	    $this->exec($vars, $steps, 'BandTextExtent', $IMCommand, 3, $steps['BandTextTrim']);
	    //$this->exec($vars, $steps, 'TextReliefDistortExtent', $IMCommand, 3, $steps['TextReliefDistortMore']);
	    //header('Content-type: image/png');
	    //return $steps['TextReliefExtent'];
	    /*}*/
		//return $steps['BandTextExtent'];
	    
	    //$steps['BandComplete'] = $steps['BandTextTrim'];
	    $steps['BandComplete'] = $steps['BandTextExtent'];
	    
	    
	    //$IMCommand = BIN_PATH.'composite ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    //$IMCommand .= '	png:- ';
	    //$this->exec($vars, $steps, 'BandComplete', $IMCommand, 3, $steps['TextMaskExtent'].$steps['TextReliefExtent']);
	    //return $steps['BandNoText'];
	    
	
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	//////////////////////////////////////// EMPTY TEXT ////////////////////////////////////////////
	
	//} else {
	    
	    
	//    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	    //$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.'plain.png ';
	//    $IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png ';
	//    $IMCommand .= '	png:- ';
	//    $this->exec($vars, $steps, 'BandComplete', $IMCommand, 2);
	    //return $steps['Band'];
	    
	//}
	
	
	
	//if(empty($options['lclipart']) && empty($options['rclipart']) && empty($options['text'])) {
	//    $IMCommand = BIN_PATH.IMAGEMAGICK_BIN.' ';
	//    //$IMCommand .= '	'.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.$options['bandImagesDir'].DIRECTORY_SEPARATOR.'plain.png ';
	//    $IMCommand .= '	\''.TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png\' ';
	//    $IMCommand .= '	png:- ';
	//    $this->exec($vars, $steps, 'BandComplete', $IMCommand, 2);
	    //return $steps['Band'];
	//}
	
	if(empty($options['lclipart']) && empty($options['rclipart']) && empty($options['text'])) {
	    $steps['BandComplete'] = file_get_contents(TPT_CACHE_DIR.DIRECTORY_SEPARATOR.'empty.png');
	} else {
	    //$cfile = $options['cfile'];
	    //var_dump($cfile);die();
	    if(!empty($options['cfile']) && !(isDump() && !empty($vars['config']['dev']['debugpreviews_purge'])) && (empty($_GET['debug_purge']))) {
		file_put_contents($options['cfile'], $steps['BandComplete']);
	    }
	}
	
	//header('Content-type: image/'.$options['format']);
	if(($options['bandStyle'] != 7)) {
	//header('Content-type: image/png');
	}

	//die();
        return $steps['BandComplete'];
        
    }
    
}